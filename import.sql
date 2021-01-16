DROP TABLE IF EXISTS `prefix_ebcms_fragment_content`;
CREATE TABLE `prefix_ebcms_fragment_content` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '节点ID',
 `fragment_id` varchar(80) NOT NULL DEFAULT '' COMMENT '碎片id',
 `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
 `description` varchar(255) NOT NULL DEFAULT '',
 `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
 `extra` text NOT NULL,
 `priority` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `redirect_uri` varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;