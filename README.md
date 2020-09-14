What is it
==========

Docker container for building personal feeds

Installation
============

```bash
docker run \
-p 80:80/tcp \
-e FEED_HOST=feed \
-e PG_HOST=db \
-e PG_PORT=5432 \
-e PG_DATABASE=feed_db \
-e PG_USER=user \
-e PG_PASSWORD=password \
-d perfumerlabs/feed:v1.0.0
```

Database must be created before container startup.

Environment variables
=====================

- FEED_HOST - server domain (without http://). Required.
- PHP_PM_MAX_CHILDREN - number of FPM workers. Default value is 10.
- PHP_PM_MAX_REQUESTS - number of FPM max requests. Default value is 500.
- PG_HOST - PostgreSQL host. Required.
- PG_PORT - PostgreSQL port. Default value is 5432.
- PG_DATABASE - PostgreSQL database name. Required.
- PG_USER - PostgreSQL user name. Required.
- PG_PASSWORD - PostgreSQL user password. Required.

Volumes
=======

This image has no volumes.

If you want to make any additional configuration of container, mount your bash script to /opt/setup.sh. This script will be executed on container setup.

Software
========

1. Ubuntu 16.04 Xenial
1. Nginx 1.16
1. PHP 7.4

Database tables
===============

After setup there are 1 predefined table in database:

### feed_collection

Registry of collections. Fields:

- name [string] - Name of collection

API Reference
=============

### Create collection

`POST /collection`

Parameters (json):
- name [string,required] - name of the collection.

Request example:

```json
{
    "name": "foobar"
}
```

Response example:

```json
{
    "status": true
}
```

### Create record

`POST /record`

Request parameters (json):
- collection [string,required] - name of the collection.
- recipient [string,required] - name of record recipient.
- sender [string,optional] - name of record author.
- thread [string,optional] - additional field for tagging record.
- title [string,optional] - title of record.
- text [string,optional] - text of record.
- image [string,optional] - image of record.
- payload [json,optional] - any JSON-serializable content.

Request example:

```json
{
    "collection": "foobar",
    "recipient": "client1",
    "title": "Hello"
}
```

Response parameters (json):
- id [integer] - unique identity of inserted document.

Response example:

```json
{
    "status": true,
    "content": {
        "record": {
            "id": 1
        }
    }
}
```

### Get records

`GET /records`

Request parameters (json):
- collection [string,required] - name of the collection.
- recipient [string,optional] - name of the recipient.
- sender [string,optional] - name of the sender.
- thread [string,optional] - name of the thread.
- id [integer,optional] - the id of document to start from.

Request example:

```json
{
    "collection": "foobar",
    "recipient": "client1"
}
```

Response example:

```json
{
    "status": true,
    "content": {
        "records": [
            {
                "id": 1,
                "recipient": "client1",
                "title": "Hello",
                "is_read": false
            }
        ]
    }
}
```

### Read a record

`POST /record/read`

Request parameters (json):
- collection [string,required] - name of the collection.
- id [integer,required] - id of the record.

Request example:

```json
{
    "collection": "foobar",
    "id": 1
}
```

Response example:

```json
{
    "status": true
}
```
