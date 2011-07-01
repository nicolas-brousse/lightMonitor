/*
 Navicat Premium Data Transfer

 Source Server         : sqlite: light_monitor
 Source Server Type    : SQLite
 Source Server Version : 3007005
 Source Database       : main

 Target Server Type    : SQLite
 Target Server Version : 3007005
 File Encoding         : utf-8

 Date: 07/01/2011 19:41:02 PM
*/

PRAGMA foreign_keys = false;

-- ----------------------------
--  Table structure for "servers"
-- ----------------------------
DROP TABLE IF EXISTS "servers";
CREATE TABLE "servers" (
	 "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	 "name" text(250,0) NOT NULL,
	 "ip" text(15,0) NOT NULL,
	 "created_at" integer(11,0) NOT NULL,
	 "updated_at" integer(11,0) NOT NULL
);

-- ----------------------------
--  Indexes structure for table "servers"
-- ----------------------------
CREATE INDEX "id" ON servers (id);

PRAGMA foreign_keys = true;
