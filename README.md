# Advent of Code 2024 ~ PHP

## Benchmarks

| Day       | Part 1                 | Part 2                 | Total                  |
| :-------: | ---------------------: | ---------------------: | ---------------------: |
| 1         | 0.00059 sec<br>0.10 MB | 0.00034 sec<br>0.10 MB | 0.00093 sec<br>0.20 MB |
| 2         | 0.00110 sec<br>0.06 MB | 0.00264 sec<br>0.06 MB | 0.00374 sec<br>0.12 MB |
| **TOTAL** | 0.00169 sec<br>0.16 MB | 0.00298 sec<br>0.16 MB | 0.00467 sec<br>0.32 MB |

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