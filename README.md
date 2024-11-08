
Os endpoints estão documentados com swagger na url http://localhost:8000/api/documentation, mas tb com arquivos .http na pasta ./.http
Basta acrescentar a extensão rest client ao vscode

Após o build a aplicação estará disponível em http://localhost:8000/
## build

´´´
cp .env.example .env
docker-compose up -d --build
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
´´´
