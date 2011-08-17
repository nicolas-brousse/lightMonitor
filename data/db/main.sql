/*
 Navicat Premium Data Transfer

 Source Server         : sqlite: light_monitor
 Source Server Type    : SQLite
 Source Server Version : 3007005
 Source Database       : main

 Target Server Type    : SQLite
 Target Server Version : 3007005
 File Encoding         : utf-8

 Date: 08/17/2011 21:35:11 PM
*/

PRAGMA foreign_keys = false;

-- ----------------------------
--  Table structure for "servers"
-- ----------------------------
DROP TABLE IF EXISTS "servers";
CREATE TABLE "servers" (
	 "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	 "servername" text NOT NULL,
	 "ip" text NOT NULL,
	 "protocol" integer NOT NULL,
	 "port" integer,
	 "login" text,
	 "pass" text,
	 "created_at" integer NOT NULL,
	 "updated_at" integer NOT NULL
);

-- ----------------------------
--  Table structure for "softwares"
-- ----------------------------
DROP TABLE IF EXISTS "softwares";
CREATE TABLE "softwares" (
	 "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	 "server_id" integer NOT NULL,
	 "label" text NOT NULL,
	 "port" integer(6,0) NOT NULL,
	 "status" integer(1,0) NOT NULL DEFAULT 0,
	 "created_at" integer NOT NULL,
	 "updated_at" integer NOT NULL,
	 "checked_at" integer,
	CONSTRAINT "fk_server_id" FOREIGN KEY ("server_id") REFERENCES "servers" ("id") ON DELETE CASCADE
);

-- ----------------------------
--  Table structure for "users"
-- ----------------------------
DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
	 "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	 "username" text(255,0) NOT NULL,
	 "passwd" text NOT NULL,
	 "passwd_salt" text NOT NULL,
	 "created_at" integer(11,0) NOT NULL,
	 "updated_at" integer(11,0) NOT NULL,
	 "logged_at" integer(11,0) NOT NULL
);

-- ----------------------------
--  Indexes structure for table "servers"
-- ----------------------------
CREATE UNIQUE INDEX "servers_ip" ON servers (ip ASC);
CREATE UNIQUE INDEX "servers_servername" ON servers (servername ASC);

-- ----------------------------
--  Indexes structure for table "softwares"
-- ----------------------------
CREATE UNIQUE INDEX "softwares_id" ON softwares (id ASC);

-- ----------------------------
--  Indexes structure for table "users"
-- ----------------------------
CREATE UNIQUE INDEX "users_id" ON users (id ASC);

PRAGMA foreign_keys = true;
