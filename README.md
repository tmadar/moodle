moodle
======



If you've already installed Moodle you'll need run these scripts to add these new tables.

CREATE TABLE `mdl_class_student_xp_table` (
  `uid` bigint(11) NOT NULL AUTO_INCREMENT,
  `userid` bigint(11) DEFAULT NULL,
  `class_metricid` bigint(11) DEFAULT NULL,
  `xp` bigint(11) DEFAULT NULL,
  `timeaquired` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='this is the table that holds the students xp records';

CREATE TABLE `mdl_post_metrix` (
  `param0` bigint(10) DEFAULT NULL,
  `param1` decimal(10,5) DEFAULT NULL,
  `param2` decimal(10,5) DEFAULT NULL,
  `param3` decimal(10,5) DEFAULT NULL,
  `param4` decimal(10,5) DEFAULT NULL,
  KEY `id_idx` (`param0`),
  CONSTRAINT `id` FOREIGN KEY (`param0`) REFERENCES `mdl_forum_posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='		';

CREATE TABLE `mdl_metrix` (
  `metrixid` bigint(11) NOT NULL DEFAULT '0',
  `courseid` bigint(20) DEFAULT NULL,
  `name` longtext COLLATE utf8_unicode_ci,
  `levels` bigint(10) DEFAULT '0',
  `xp_per_level` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`metrixid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This should be the table for specific class metrics.';
