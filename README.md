## サービス名
coachtechフリマ

## プロジェクト概要
・フリマアプリ「coachtechフリマ」の開発プロジェクト<br> 
・ユーザー同士で商品の出品・購入ができるWebアプリ<br>  
・シンプルで使いやすいUIを重視した設計<br>  
・商品一覧・詳細・検索機能によりスムーズな商品探索が可能<br>  
・ユーザーは会員登録後、出品・購入・コメント・お気に入り登録が可能<br>  
・Laravelを用いたWebアプリケーション開発の学習を目的として作成<br>

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
・phpMyAdmin：http://localhost:8080/<br>

### 開発環境
・商品一覧画面（トップ画面）：http://localhost/<br>
・会員登録画面：http://localhost/register<br>
・ログイン画面：http://localhost/login<br>

## 使用技術(実行環境)
・PHP:8.1.34<br>
・Laravel 8.83.29<br>
・MySQL 8.0.26<br>
・nginx 1.21.1<br>

## 未認証ユーザーのログイン情報
・商品一覧画面（トップ画面）：http://localhost/<br>
・会員登録画面：http://localhost/register<br>

## 認証ユーザーのログイン情報
・ログイン画面：http://localhost/login<br>

## ER図
<img width="709" height="766" alt="ER図" src="https://github.com/user-attachments/assets/b3562f8f-9d8c-4c79-a325-534e45e1d486" />
