# Ball Don't Lie

## Credenciais de usuário para login

- Email: admin@email.com
- Password: 12345678

## Iniciar o projeto com Docker

1. Execute o comando `cp .env.example .env` para copiar o arquivo de exemplo de ambiente.
2. Execute o comando `docker-compose up -d --build` para criar os containers.
3. Execute o comando `docker-compose exec app composer install` para instalar as dependências do projeto.
4. Execute o comando `docker-compose exec app php artisan key:generate` para gerar a chave do projeto.
5. Execute o comando `docker-compose exec app php artisan migrate` para criar as tabelas do banco de dados.
6. Execute o comando `docker-compose exec app php artisan db:seed` para popular as tabelas do banco de dados com dados fictícios.

## Passo a passo para executar o projeto

1. Execute o comando `docker-compose up -d` para criar os containers.
2. Abra o navegador/postman e use a URL `http://localhost:8888`.
3. Faça o faça o login com as credenciais de usuário fornecidas;
4. Use o bearer token recebido para usar as outras rotas

## Executar Jobs de importação de dados

1. Execute o comando `docker-compose exec app php artisan import-from-api` para executar os jobs de importação de dados, também pode ser executado com o parametro `--type=` que pode receber: teams, games ou players.

### API Documentation

## Authentication
### Login
- **Method**: POST
- **URL**: `http://localhost:8888/api/login`
- **Body**: 
```json
{
    "email": "admin@email.com",
    "password": "12345678"
}
```

### Logout
- **Method**: POST
- **URL**: `http://localhost:8888/api/logout`
- **Authentication**: Bearer Token

## External API Tokens
### Create Token
- **Method**: POST
- **URL**: `http://localhost:8888/api/external-api-tokens`
- **Authentication**: Bearer Token
- **Body**:
```json
{
    "name": "Sistema 1"
}
```

### Update Token
- **Method**: PUT
- **URL**: `http://localhost:8888/api/external-api-tokens/{id}`
- **Headers**: X-Authorization
- **Body**:
```json
{
    "name": "Teste 1"
}
```

### Delete Token
- **Method**: DELETE
- **URL**: `http://localhost:8888/api/external-api-tokens/{id}`
- **Headers**: X-Authorization

## Teams
### Create Team
- **Method**: POST
- **URL**: `http://localhost:8888/api/teams`
- **Headers**: X-Authorization
- **Body**:
```json
{
    "externalId": 3,
    "conference": "East",
    "division": "Southeast",
    "city": "Atlanta",
    "name": "Hawks",
    "fullName": "Atlanta Hawks",
    "abbreviation": "ATL"
}
```

### Update Team
- **Method**: PUT
- **URL**: `http://localhost:8888/api/teams/{id}`
- **Body**:
```json
{
    "externalId": 5,
    "conference": "East",
    "division": "Southeast",
    "city": "Atlanta",
    "name": "Hawks",
    "fullName": "Atlanta Hawks",
    "abbreviation": "ATL"
}
```

### Get All Teams
- **Method**: GET
- **URL**: `http://localhost:8888/api/teams`
- **Query Parameters**: page
- **Authentication**: Bearer Token or X-Authorization

### Get Team by ID
- **Method**: GET
- **URL**: `http://localhost:8888/api/teams/{id}`

### Delete Team
- **Method**: DELETE
- **URL**: `http://localhost:8888/api/teams/{id}`

## Players
### Create Player
- **Method**: POST
- **URL**: `http://localhost:8888/api/players`
- **Body**:
```json
{
    "externalId": 20,
    "firstName": "Stephen",
    "lastName": "Curry",
    "position": "G",
    "height": "6-2",
    "weight": "185",
    "jerseyNumber": "30",
    "college": "Davidson",
    "country": "USA",
    "draftYear": 2009,
    "draftRound": 1,
    "draftNumber": 7,
    "teamId": 1
}
```

### Update Player
- **Method**: PUT
- **URL**: `http://localhost:8888/api/players/{id}`
- **Body**: Same as Create Player

### Get All Players
- **Method**: GET
- **URL**: `http://localhost:8888/api/players`

### Get Player by ID
- **Method**: GET
- **URL**: `http://localhost:8888/api/players/{id}`

### Delete Player
- **Method**: DELETE
- **URL**: `http://localhost:8888/api/players/{id}`

## Games
### Create Game
- **Method**: POST
- **URL**: `http://localhost:8888/api/games`
- **Body**:
```json
{
    "externalId": 1,
    "date": "2018-10-16",
    "datetime": "2018-10-17 00:00:00+00",
    "season": 2018,
    "status": "Final",
    "period": 4,
    "time": " ",
    "postseason": false,
    "homeTeamScore": 105,
    "visitorTeamScore": 87,
    "homeTeamId": 1,
    "visitorTeamId": 2
}
```

### Get All Games
- **Method**: GET
- **URL**: `http://localhost:8888/api/games`

### Get Game by ID
- **Method**: GET
- **URL**: `http://localhost:8888/api/games/{id}`

### Delete Game
- **Method**: DELETE
- **URL**: `http://localhost:8888/api/games/{id}`

## Licença

Este projeto é licenciado sob a licença MIT. Para mais informações, por favor, leia o arquivo [LICENSE](LICENSE).


