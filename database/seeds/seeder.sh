alias jcurl="curl -H 'Accept: text/json' -H 'Content-Type: text/json' -X POST"
URL=http://localhost:8000/api

jcurl --data '{"name": "sbjt_name", "label": "Subject Name", "fieldType": "BASIC"}' $URL/fields
jcurl --data '{"name": "sbjt_age", "label": "Subject Age", "fieldType": "BASIC"}' $URL/fields

jcurl -X GET $URL/fields