-- TABLE
CREATE TABLE "migrations" ("id" integer not null primary key autoincrement, "migration" varchar not null, "batch" integer not null);
CREATE TABLE "password_resets" ("email" varchar not null, "token" varchar not null, "created_at" datetime null);
CREATE TABLE "permissions" ("id" integer not null primary key autoincrement, "name" varchar not null, "display_name" varchar null, "description" varchar null, "created_at" datetime null, "updated_at" datetime null);
CREATE TABLE "permission_role" ("permission_id" integer not null, "role_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade on update cascade, foreign key("role_id") references "roles"("id") on delete cascade on update cascade, primary key ("permission_id", "role_id"));
CREATE TABLE "roles" ("id" integer not null primary key autoincrement, "name" varchar not null, "display_name" varchar null, "description" varchar null, "created_at" datetime null, "updated_at" datetime null);
CREATE TABLE "role_user" ("user_id" integer not null, "role_id" integer not null, foreign key("user_id") references "users"("id") on delete cascade on update cascade, foreign key("role_id") references "roles"("id") on delete cascade on update cascade, primary key ("user_id", "role_id"));
CREATE TABLE sqlite_sequence(name,seq);
CREATE TABLE "users" ("id" integer not null primary key autoincrement, "name" varchar not null, "slug" varchar not null, "email" varchar not null, "email_verified_at" datetime null, "password" varchar not null, "remember_token" varchar null, "created_at" datetime null, "updated_at" datetime null, "deleted_at" datetime null);
CREATE TABLE "user_loans" ("id" integer not null primary key autoincrement, "slug" varchar not null, "loan_amount" numeric not null default '0', "monthly_income" numeric not null default '0', "loan_paid" numeric not null default '0', "balance_amount" numeric not null default '0', "tenure_by_week" integer not null default '0', "status" integer not null default '0', "user_id" integer null, "sactioned_date" date null, "created_at" datetime null, "updated_at" datetime null, "deleted_at" datetime null);
CREATE TABLE "user_loan_repayments" ("id" integer not null primary key autoincrement, "slug" varchar not null, "user_id" integer null, "loan_id" integer null, "amount_paid" numeric not null default '0', "paid_date" date null, "weekly_due_date" date null, "remaining_balance" numeric not null default '0', "status" integer not null default '0', "created_at" datetime null, "updated_at" datetime null, "deleted_at" datetime null);
 
-- INDEX
CREATE INDEX "password_resets_email_index" on "password_resets" ("email");
CREATE UNIQUE INDEX "permissions_name_unique" on "permissions" ("name");
CREATE UNIQUE INDEX "roles_name_unique" on "roles" ("name");
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
 
-- TRIGGER
 
-- VIEW
 
