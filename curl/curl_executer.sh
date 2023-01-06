#!/bin/bash

counter=0
maxfiles=10
for filename_json in ../processing/url/*.json; do
  [ -e "$filename_json" ] || continue

  if [[ $counter -gt $maxfiles ]]; then
    break
  fi
  counter=$(($counter + 1))
  #  echo "$filename_json"
  url=$(jq .url -r "$filename_json")
  filename_output="$(date +%s)_$(jq .type -r "$filename_json")_$(jq .check_id -r "$filename_json")_$(jq .check_item_id -r "$filename_json").txt"
#  echo $filename_json
  #  echo "$url"
  #  url="https://bing.com"
  /usr/bin/curl -w "@curl-format.txt" --request GET --compressed -Lvs -o /dev/null ${url} >../processed/url/$filename_output 2>&1
  mv $filename_json ../processing/url/done/
  #echo $test > a.txt 2>&1
  # ... rest of the loop body
done
