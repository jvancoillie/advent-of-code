# PHP Tools to help resolving '[Advent of Code](https://adventofcode.com/)' puzzles 

## Commands :  

### bin/console puzzle:make

Description: 
 Create the input data and structure for a given puzzle event

Usage:  
 puzzle:make [options]  
  
Options:  
 `-y, --year=YEAR`       the year of the event [default: "2020"]  
 `-d, --day=DAY`         the day of the event [default: "26"]  
 `--no-data`             use this option to disable input data fetching  
  

## execute puzzle resolver :  
Description:  
 Outputs the solutions of a Puzzles for a given event  

Usage:  
 puzzle:resolve [options]  

Options:  
 `-y, --year=YEAR`        the year of the event [default: "2020"]  
 `-d, --day=DAY`          the day of the event [default: "26"]  
 `--test`                 If set, run with test input  


## Docker env
`build` docker-compose build  
`up`    docker-compose up -d  
`exec`  docker-compose exec advent bash  
 