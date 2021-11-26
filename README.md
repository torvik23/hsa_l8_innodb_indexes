# HSA L8: InnoDB Indexes

## Overview
This is an example project to show how table with InnoDB engine works without indexes, with BTREE and HASH indexes. Also, it shows number of operations per second with different values of innodb_flush_log_at_trx_commit.

## Getting Started

### Preparation
1. Install [Siege](https://github.com/JoeDog/siege) benchmarking tool. If you are using macOS, you can install it via brew
```bash
  brew install siege
```

2. Run the docker containers.
```bash
  docker-compose up -d
```

Be sure to use ```docker-compose down -v``` to cleanup after you're done with tests.

3. Generate test users. I used php script to generate it via next command:

```bash
docker exec -it php-fpm php bin/console.php user:generate 10000
```

I created user table with 40M entries.

```mysql
mysql> SELECT count(`id`) AS `number_of_users` FROM `user`;
+-----------------+
| number_of_users |
+-----------------+
|        40000000 |
+-----------------+
1 row in set (27.10 sec)
```

## Task #1
* Compare SELECT performance using different scenarios.

Using mysql CLI client I checked next scenarios:
### 1. Without index.

#### 1.1 Select with 'equal' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` = '1997-08-03';
+-------------+
| count(`id`) |
+-------------+
|        1834 |
+-------------+
1 row in set (16.69 sec)
```

#### 1.2 Select with 'less then' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` < '1984-06-28';
+-------------+
| count(`id`) |
+-------------+
|    15440559 |
+-------------+
1 row in set (17.82 sec)
```

#### 1.3 Select with 'between' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` >= '2000-01-01' AND `dob` <= '2021-01-01';
+-------------+
| count(`id`) |
+-------------+
|    13810429 |
+-------------+
1 row in set (17.13 sec)
```

### 2. With BTREE index.

First I created BTREE index with the next command:
```sql
mysql> CREATE INDEX USER_DOB_BTREE_INDEX USING BTREE ON `user` (`dob`);
Query OK, 0 rows affected (2 min 24.43 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

#### 2.1 Select with 'equal' operation.
```sql
mysql>  SELECT count(`id`) from `user` where `dob` = '1997-08-03';
+-------------+
| count(`id`) |
+-------------+
|        1834 |
+-------------+
1 row in set (0.00 sec)
```

#### 2.2 Select with 'less then' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` < '1984-06-28';
+-------------+
| count(`id`) |
+-------------+
|    15440559 |
+-------------+
1 row in set (6.16 sec)
```

#### 2.3 Select with 'between' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` >= '2000-01-01' AND `dob` <= '2021-01-01';
+-------------+
| count(`id`) |
+-------------+
|    13810429 |
+-------------+
1 row in set (6.66 sec)
```

Before next scenario need to remove BTREE index:
```sql
mysql> DROP INDEX USER_DOB_BTREE_INDEX ON `user`;
Query OK, 0 rows affected (0.06 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

### 3. With HASH index.

I created HASH index with the next command:
```sql
mysql> CREATE INDEX USER_DOB_HASH_INDEX USING HASH ON `user` (`dob`);
Query OK, 0 rows affected (1 min 39.43 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

#### 3.1 Select with 'equal' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` = '1997-08-03';
+-------------+
| count(`id`) |
+-------------+
|        1834 |
+-------------+
1 row in set (0.00 sec)
```

#### 3.2 Select with 'less then' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` < '1984-06-28';
+-------------+
| count(`id`) |
+-------------+
|    15440559 |
+-------------+
1 row in set (4.98 sec)
```

#### 3.3 Select with 'between' operation.
```sql
mysql> SELECT count(`id`) from `user` where `dob` >= '2000-01-01' AND `dob` <= '2021-01-01';
+-------------+
| count(`id`) |
+-------------+
|    13810429 |
+-------------+
1 row in set (5.83 sec)
```

And I removed HASH index:
```sql
mysql> DROP INDEX USER_DOB_HASH_INDEX ON `user`;
Query OK, 0 rows affected (0.06 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

Task #1 conclusions: HASH index has shown better results than BTREE index and without any index.

## Task #2
* Compare INSERT performance by changing innodb_flush_log_at_trx_commit value.

Number of users per operation: 10, 25, 50, 100, 1000.

Duration of a scenario: 30 seconds.

Run the test scenarios via siege command:
```bash
siege -b -c10 -t30s http://localhost:8080/user/generate
siege -b -c25 -t30s http://localhost:8080/user/generate
siege -b -c50 -t30s http://localhost:8080/user/generate
siege -b -c100 -t30s http://localhost:8080/user/generate
siege -b -c1000 -t30s http://localhost:8080/user/generate
```

| innodb_flush_log_at_trx_commit | 10 | 25 | 50 | 100 | 1000 |
| :----------------------------: |:---:|:---:|:---:|:---:|:---:|
| 0 | 1.24 | 2.10 | 5.83 | 9.10 | 14.36 |
| 1 | 4.69 | 5.74 | 9.78 | 14.22 | 18.77 |
| 2 | 2.01 | 4.15 | 7.61 | 12.52 | 15.39 |
