# Events

Sistema de cadastro de eventos.

## Requisitos

- Docker
- Docker Compose

## Executar pela primeira vez o projeto

`` $ chmod +x run_intitial.sh``

`` $ ./run_intitial.sh``

## Executar demais vezes

`` $ docker-compose up -d ``

## Endpoints API 

### User 

* [POST] http://localhost:8000/api/login
* [POST] http://localhost:8000/api/register
  
 ```json
  {
  "username": "test2",
  "password": "12345678",
  "email": "test@gmail.com"
  }
```

### Events

* [GET] http://localhost:8000/api/events
* [GET] http://localhost:8000/api/events/{id}
* [POST] http://localhost:8000/api/event

  ```json 
  {
  "title": "Teste",
  "date_start": "2021-06-12 02:00:22",
  "date_end": "2021-05-15 00:00:00",
  "description": "Description"
  } 
  ```
* [PUT] http://localhost:8000/api/events/{id}

  ```json 
  {
  "title": "Teste",
  "date_start": "2021-06-12 02:00:22",
  "date_end": "2021-05-15 00:00:00",
  "description": "Description"
  } 
  ```
  
* [DELETE] http://localhost:8000/api/events/{id}

### Talks

* [GET] http://localhost:8000/api/talks
* [GET] http://localhost:8000/api/talks/{id}
* [POST] http://localhost:8000/api/talk

  ```json 
  {
  "title": "Teste Talk 2131231",
  "date": "2021-06-12",
  "hour_start": "12:00:00",
  "hour_end": "15:00:00",
  "description": "Description",
  "event_id": "6afad7b7-6320-11eb-8b7a-0242ac180002",
  "speaker_id": "6afad7b7-6320-11eb-8b7a-0242ac18ae43"
  }
  ```
  
* [PUT] http://localhost:8000/api/talks/{id}

  ```json
   {
  "title": "Teste Talk 2131231",
  "date": "2021-06-12",
  "hour_start": "12:00:00",
  "hour_end": "15:00:00",
  "description": "Description",
  "event_id": "6afad7b7-6320-11eb-8b7a-0242ac180002",
  "speaker_id": "6afad7b7-6320-11eb-8b7a-0242ac18ae43"
  }
  ```

* [DELETE] http://localhost:8000/api/talks/{id}

## Fakes Values

### User
- Email: test@me.com
- Password: 12345678

### Speaker
- ID: 0218d78e-6438-11eb-aa73-0242ac180002
