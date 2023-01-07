#!/bin/bash

counter=0
maxfiles=10
echo "Hello 1"
for filename_json in /var/www/html/processing/url/*.json; do
  [ -e "$filename_json" ] || continue

  if [[ $counter -gt $maxfiles ]]; then
    break
  fi
  echo "Hello 2"
  counter=$(($counter + 1))
  #  echo "$filename_json"
  url=$(jq .url -r "$filename_json")
  filename_output="TEST_$(date +%s)_$(jq .type -r "$filename_json")_$(jq .check_id -r "$filename_json")_$(jq .check_item_id -r "$filename_json").txt"
  echo "Hello 3"
  #  echo $filename_json
  #  echo "$url"
  #  url="https://bing.com"
  /usr/bin/curl --request GET --compressed -Lvs -o /dev/null https://prometheus.nicastro.io/cron_test >/var/www/html/processed/url/$filename_output 2>&1
  echo "Hello 4"
  #mv $filename_json ../processing/url/done/
  #echo $test > a.txt 2>&1
  # ... rest of the loop body
done
echo "Hello 5"
