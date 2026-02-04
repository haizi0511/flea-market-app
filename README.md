# アプリケーション名
flea-market-app

## 環境構築
Dockerビルド<br>
・git clone git@github.com:haizi0511/flea-market-app.git<br>
・docker-compose up -d --build<br>

### Laravel環境構築
・docker-compose exec php bash<br>
・composer install<br>
・cp .env.example .env、環境変数を変更<br>
・php artisan key:generate<br>
・php artisan migrate<br>
・php artisan db:seed<br>

### 開発環境
・商品一覧画面（トップ画面）：http://localhost/<br>
・会員登録画面：http://localhost/register<br>
・ログイン画面：http://localhost/login<br>

## 使用技術(実行環境)
・PHP:8.1.34<br>
・Laravel 8.83.29<br>
・MySQL 8.0.26<br>
・nginx 1.21.1<br>
