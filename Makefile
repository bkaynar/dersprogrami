.PHONY: help build up down restart logs shell migrate seed fresh queue

help: ## Yardım
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Docker image'larını build et
	docker-compose build

up: ## Container'ları başlat
	docker-compose up -d

up-dev: ## Container'ları dev modunda başlat (Vite dahil)
	docker-compose --profile dev up -d

down: ## Container'ları durdur
	docker-compose down

restart: ## Container'ları yeniden başlat
	docker-compose restart

logs: ## Logları göster
	docker-compose logs -f

logs-app: ## Uygulama loglarını göster
	docker-compose logs -f app

logs-queue: ## Queue loglarını göster
	docker-compose logs -f queue

shell: ## App container'ına bağlan
	docker-compose exec app bash

mysql: ## MySQL'e bağlan
	docker-compose exec mysql mysql -u laravel -psecret ders_programi

migrate: ## Migration'ları çalıştır
	docker-compose exec app php artisan migrate

seed: ## Seeder'ları çalıştır
	docker-compose exec app php artisan db:seed

fresh: ## Veritabanını sıfırla ve seed et
	docker-compose exec app php artisan migrate:fresh --seed

queue: ## Queue worker'ı manuel başlat
	docker-compose exec app php artisan queue:work --timeout=900

cache-clear: ## Cache temizle
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan view:clear

key-generate: ## App key oluştur
	docker-compose exec app php artisan key:generate

install: ## İlk kurulum
	cp .env.docker .env
	docker-compose build
	docker-compose up -d
	sleep 10
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate
	@echo "✅ Kurulum tamamlandı! http://localhost:8080 adresinden erişebilirsiniz."
