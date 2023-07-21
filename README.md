## InternalMarksEvaluator

This is a demonstration project for undergraudate students to introduce and utilize REST API written in PHP. It receives
rule strategy for marks evaluation in colleges, atleast in Nepal. These rules are used to evaluate the obtabined marks
of the students in different exams and aggregate them through API. Different REST API methods are used for their 
purpose.

### 1. Create Rule
```
curl --location 'localhost/expression/index.php' \
--header 'Content-Type: application/json' \
--data '{
    "teacher_id": "aa97-ba8a",
    "subject_id": "4409-9989",
    "year": 2023,
    "department": "Computer",
    "section": "A",
    "semester": "fall",
    "category": "Theory",
    "rule": "attendance * 0.70 + ut * 1.20 + assginment * 1.20 + assessment * 1.20 * presentation * 0.5",
    "description": "weighted evaluation metric for the theory evaluation of web technology"
}'
```

### 2. Read Variables
All the extracted variables from the given rule.
```
curl --location 'localhost/expression/index.php?action=variables&rule_id=25bacdd9-b114-4b2b-a5b5-fe0246266024'
```

### 3. List Rules
```
curl --location --request GET 'localhost/expression/index.php?action=rules' \
--header 'Content-Type: application/json' \
--data ' {
	    "teacher_id": "aa97-ba8a",
        "subject_id": "4409-9989",
	    "year": 2023
 }'
```

### 4. Compute Marks
```
curl --location --request GET 'localhost/expression/index.php?action=compute&rule_id=25bacdd9-b114-4b2b-a5b5-fe0246266024' \
--header 'Content-Type: application/json' \
--data ' {
	    "attendance": 4,
	    "ut": 5,
	    "assessment": 10, 
	    "assginment": 6,
        "presentation": 10
 }'
```

### 5. Update Rule
```
curl --location --request PATCH 'localhost/expression/index.php' \
--header 'Content-Type: application/json' \
--data '{
    "rule_id": "25bacdd9-b114-4b2b-a5b5-fe0246266024",
    "rule": "attendance * 0.70 + ut * 1.20 + assginment * 1.20 + assessment * 1.20 * presentation * 0.5"
}'
```

