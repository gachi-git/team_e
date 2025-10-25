# 大学生向けQ&Aプラットフォーム

大学生向けの質問・回答プラットフォームです。

## 主な機能

### ユーザー管理
- **大学別ユーザー登録**: 大学と紐づいたユーザーアカウント作成
- **認証システム**: Laravel Breezeによる安全なログイン・ログアウト機能
- **プロフィール管理**: ユーザー情報の編集とアカウント管理

### 質問・回答システム
- **質問投稿**: タイトルと詳細内容を含む質問の投稿機能
- **ハッシュタグ機能**: 質問に複数のタグを付けてカテゴリ分け
- **回答投稿**: 質問に対する回答の投稿（回答者情報付き）
- **ベストアンサー選択**: 質問投稿者による最適回答の選定機能
- **質問編集**: 投稿した質問内容の編集機能

### 検索・フィルタリング
- **ハッシュタグ検索**: タグによる質問の絞り込み機能

### 通知・エンゲージメント
- **通知システム**: 自分の質問に新しい回答が付いた際のデータベース通知
- **閲覧数トラッキング**: 質問の閲覧数自動カウント機能

### セキュリティ・権限管理
- **認可システム**: 質問の編集・削除権限の制御

## 技術スタック

- **バックエンド**: Laravel 12 (PHP 8.4)
- **フロントエンド**: Blade テンプレート + Tailwind CSS + Alpine.js
- **データベース**: MySQL 8.0
- **キャッシュ**: Redis
- **認証**: Laravel Breeze
- **テスト**: Pest (PHP Testing Framework)
- **メール**: Mailpit (開発環境)
- **コンテナ**: Docker + Laravel Sail

## 環境構築

### 必要な環境
- Docker
- Docker Compose

### サービス構成
- **laravel.test**: メインのLaravelアプリケーション (http://localhost)
- **mysql**: MySQL 8.0データベース (ポート: 3306)
- **redis**: Redisキャッシュサーバー (ポート: 6379)
- **phpmyadmin**: データベース管理 (http://localhost:8080)
- **selenium**: ブラウザテスト用

### セットアップ手順

1. プロジェクトのクローン
```bash
git clone <repository-url>
cd team_e
```

2. Docker環境の起動
```bash
./vendor/bin/sail up -d
```

3. 初期セットアップの実行（.envファイル作成、APP_KEY生成、マイグレーション実行）
```bash
./vendor/bin/sail composer run setup
```

4. 大学データのシーディング
```bash
./vendor/bin/sail artisan db:seed --class=UniversitySeeder
```


### コンテナの管理
```bash
# 起動
./vendor/bin/sail up -d

# 停止
./vendor/bin/sail down


### テストの実行
```bash
./vendor/bin/sail composer run test
```

### Artisanコマンド
```bash
./vendor/bin/sail artisan <command>
```

## アクセスURL

- **メインアプリケーション**: http://localhost
- **phpMyAdmin**: http://localhost:8080


## データベース構造

### 主要テーブル
- `users`: ユーザー情報（university_id含む）
- `universities`: 大学情報（名前、カナ、種別）
- `questions`: 質問データ（タイトル、本文、閲覧数、best_answer_id）
- `answers`: 回答データ（本文、question_id、user_id）
- `tags`: ハッシュタグマスター
- `question_tag`: 質問とタグの多対多関係
- `notifications`: 通知データ（データベース通知）