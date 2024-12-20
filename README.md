# Advent of Code 2024 ~ PHP

## Benchmarks

| Day       | Part 1                  | Part 2                  | Total                   |
| :-------: | ----------------------: | ----------------------: | ----------------------: |
| 1         | 0.00059 sec<br>0.10 MB  | 0.00034 sec<br>0.10 MB  | 0.00093 sec<br>0.20 MB  |
| 2         | 0.00110 sec<br>0.06 MB  | 0.00264 sec<br>0.06 MB  | 0.00374 sec<br>0.12 MB  |
| 3         | 0.00013 sec<br>0.13 MB  | 0.00014 sec<br>0.18 MB  | 0.00027 sec<br>0.31 MB  |
| 4         | 0.00587 sec<br>1.74 MB  | 0.00171 sec<br>1.74 MB  | 0.00758 sec<br>3.48 MB  |
| 5         | 0.00162 sec<br>0.23 MB  | 0.00288 sec<br>0.23 MB  | 0.00450 sec<br>0.46 MB  |
| 6         | 0.00103 sec<br>1.57 MB  | 0.57224 sec<br>4.13 MB  | 0.57327 sec<br>5.70 MB  |
| 7         | 0.00350 sec<br>0.07 MB  | 0.00972 sec<br>0.10 MB  | 0.01322 sec<br>0.17 MB  |
| 8         | 0.00053 sec<br>0.10 MB  | 0.00093 sec<br>0.21 MB  | 0.00146 sec<br>0.31 MB  |
| 9         | 0.10969 sec<br>1.11 MB  | 2.11735 sec<br>2.85 MB  | 2.22704 sec<br>3.96 MB  |
| 10        | 0.00188 sec<br>0.07 MB  | 0.00217 sec<br>0.07 MB  | 0.00405 sec<br>0.14 MB  |
| 11        | 0.00119 sec<br>0.05 MB  | 0.05634 sec<br>0.39 MB  | 0.05753 sec<br>0.44 MB  |
| 12        | 0.01912 sec<br>2.11 MB  | 0.02305 sec<br>2.13 MB  | 0.04217 sec<br>4.24 MB  |
| 13        | 0.00077 sec<br>0.04 MB  | 0.00076 sec<br>0.04 MB  | 0.00153 sec<br>0.08 MB  |
| 14        | 0.00066 sec<br>0.04 MB  | 1.52412 sec<br>0.37 MB  | 1.52478 sec<br>0.41 MB  |
| 15        | 0.00439 sec<br>1.28 MB  | 0.00631 sec<br>1.40 MB  | 0.01070 sec<br>2.68 MB  |
| 16        | 0.03519 sec<br>4.31 MB  | 0.14220 sec<br>27.34 MB | 0.17739 sec<br>31.65 MB |
| 18        | 0.00298 sec<br>0.60 MB  | 0.02472 sec<br>0.50 MB  | 0.02770 sec<br>1.10 MB  |
| 19        | 1.37870 sec<br>0.10 MB  | 1.02103 sec<br>2.83 MB  | 2.39973 sec<br>2.93 MB  |
| 20        | 0.01112 sec<br>1.77 MB  | 2.71477 sec<br>1.77 MB  | 2.72589 sec<br>3.54 MB  |
| **TOTAL** | 1.58006 sec<br>15.48 MB | 8.22342 sec<br>46.44 MB | 9.80348 sec<br>61.92 MB |

## Usage

### Installation
```
composer install
```

### Running a solution
```
bin/console run 5.1
```
This will run day 5 part 1 using the `var/input/raw/day05.txt` file as input

### Running a solution with a test file
```
bin/console run 11.2 test
```
This will run day 11 part 2 using the `var/input/variations/day11-test.txt` file as input.
Note that 'test' can be any string, as long as an input file exists for that day, with that suffix.

### Creating a solution class for a specific day
```
bin/console create 5
```
This will create a `AdventOfCode\Solutions\Day05` class, and input files in `var/input` when the AOC_SESSION_ID is 
defined by the `.env` file.

### Generating benchmarks
```
bin/console benchmark
```
This will create benchmark all solutions (run each part up to 10 times, stop after 10 seconds) and update the results in
this readme file.

It is possible to only benchmark a single day and update the readme:
```
bin/console benchmark 13
```
Note that totals will always be recalculated.