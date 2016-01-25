/*
MySQL Backup
Source Host:           localhost
Source Server Version: 5.0.95
Source Database:       cc_cooler
Date:                  2016/01/25 13:36:18
*/

SET FOREIGN_KEY_CHECKS=0;
use cc_cooler;
#----------------------------
# Table structure for category
#----------------------------
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL,
  `title_heading` varchar(100) NOT NULL,
  `subtitle_heading` varchar(100) NOT NULL,
  `background_color` varchar(20) NOT NULL,
  `category_description` text NOT NULL,
  `category_image_url` varchar(255) NOT NULL,
  `contact_email` varchar(120) NOT NULL,
  `link_url` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `sort_order` smallint(6) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table category
#----------------------------


insert  into category values 
(1, 'Equipment', 'Explore', 'Global Equipment Innovation', '#F40000', 'Experience the way we serve <br />Coca-Cola to the world', 'eqp-1.png', '', 'eqp-menu.php', 1, 10), 
(2, 'Packaging', 'Explore', 'Global Packaging Innovation', '#7d7676', 'View innovative packaging solutions', 'pkg-surge.png', '', 'pkg-menu.php', 1, 20), 
(3, 'ETA', 'ETA', 'Global ETA Innovation', '#000000', 'Explore external technology that inspires the whole KO value chain', 'ETA Circle_Logo.png', '', '', 1, 30);
#----------------------------
# Table structure for color
#----------------------------
CREATE TABLE `color` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `overview_text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table color
#----------------------------


insert  into color values 
(1, 1, 'Color - sdad asd lsad khidf hdkfh sdkfh sdkfhih sdkhdf uigif rgohgou9g aadjgdf fdglfg si8d di7'), 
(2, 2, ''), 
(3, 3, '');
#----------------------------
# Table structure for color_image
#----------------------------
CREATE TABLE `color_image` (
  `id` int(11) NOT NULL auto_increment,
  `color_id` int(11) NOT NULL,
  `image_file` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table color_image
#----------------------------


insert  into color_image values 
(1, 1, 'tech-featured-image.png', 10), 
(2, 1, 'tech-featured-image.png', 20);
#----------------------------
# Table structure for homepage_image
#----------------------------
CREATE TABLE `homepage_image` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `homepage_image_url` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table homepage_image
#----------------------------


insert  into homepage_image values 
(5, 'Coke_Bkgrd_2.jpg', 1, 20), 
(4, 'Coke_Bkgrd_1.jpg', 1, 30), 
(8, 'Coke_Bkgrd_3.jpg', 1, 10);
#----------------------------
# Table structure for ibeacon
#----------------------------
CREATE TABLE `ibeacon` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `overview_text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table ibeacon
#----------------------------


insert  into ibeacon values 
(1, 1, 'iBeacon - sdad asd lsad khidf hdkfh sdkfh sdkfhih sdkhdf uigif rgohgou9g aadjgdf fdglfg si8d di7'), 
(2, 2, ''), 
(3, 3, '');
#----------------------------
# Table structure for ibeacon_image
#----------------------------
CREATE TABLE `ibeacon_image` (
  `id` int(11) NOT NULL auto_increment,
  `ibeacon_id` int(11) NOT NULL,
  `image_file` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table ibeacon_image
#----------------------------


insert  into ibeacon_image values 
(1, 1, 'tech-featured-image.png', 10), 
(2, 1, 'tech-featured-image.png', 20);
#----------------------------
# Table structure for item
#----------------------------
CREATE TABLE `item` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(10) unsigned NOT NULL default '0',
  `item_name` varchar(255) NOT NULL,
  `background_color` varchar(20) NOT NULL,
  `contact_email` varchar(120) NOT NULL,
  `gallery_description` varchar(100) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item
#----------------------------


insert  into item values 
(1, 1, 'Off Grid Cooler', '', 'clburnett@coca-cola.com', 'Cooler Gallery', 10), 
(2, 3, 'ETA 1', '0', '', '', 10), 
(7, 1, 'Coolio', '', 'clburnett@coca-cola.com', '', 20), 
(4, 2, 'Coca-Cola Life', '#4e774b', 'jay.davis@phase3mc.com', '', 10), 
(8, 1, 'Flatpack', '', '', '', 30), 
(10, 1, 'Glacier', '', 'clburnett@coca-cola.com', '', 40), 
(11, 1, 'Splashbar', '', 'clburnett@coca-cola.com', '', 50), 
(12, 1, 'Super Chill', '', '', '', 60), 
(13, 1, 'Cooler Seven', 'red', '', '', 70), 
(14, 1, 'Cooler Eight', 'red', '', '', 80), 
(15, 1, 'Cooler Nine', 'red', '', '', 90), 
(16, 1, 'Cooler Ten', 'red', '', '', 100), 
(19, 2, 'Mello Yello', '#cfa519', '', '', 20), 
(20, 2, 'Coca-Cola Summer 12 Pack', '0', '', '', 30), 
(21, 2, 'Dasani Plant Bottle', '#0969b1', '', '', 40), 
(22, 2, 'Sprite Summer Label', '#006a3a', '', '', 50), 
(24, 2, 'Dasani Plant Bottle 6 Pack', '#0969b1', '', '', 70), 
(23, 2, 'Surge', '#b3d236', '', '', 60), 
(25, 3, 'ETA 2', '0', '', '', 20), 
(26, 3, 'ETA 3', '0', '', '', 30), 
(27, 3, 'ETA 4', '0', '', '', 40), 
(28, 3, 'ETA 5', '0', '', '', 50), 
(29, 3, 'ETA 6', '0', '', '', 60);
#----------------------------
# Table structure for item_gallery_image
#----------------------------
CREATE TABLE `item_gallery_image` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL,
  `item_gallery_image_url` varchar(255) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_gallery_image
#----------------------------


insert  into item_gallery_image values 
(8, 1, 'DSC_0360_small.jpg', 100), 
(7, 1, 'DSC_0316_small.jpg', 110), 
(9, 1, 'DSC_0367_small.jpg', 90), 
(10, 1, 'DSC_0374_small.jpg', 80), 
(11, 1, 'DSC_0378_small.jpg', 70), 
(12, 1, 'DSC_0378_small.jpg', 60), 
(23, 1, 'og_pres_Page_03.jpg', 20), 
(14, 1, 'DSC_0382_small.jpg', 50), 
(15, 1, 'DSC00323_small.jpg', 40), 
(22, 1, 'og_pres_Page_09.jpg', 30), 
(24, 1, 'og_pres_Page_04.jpg', 10);
#----------------------------
# Table structure for item_image
#----------------------------
CREATE TABLE `item_image` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_image_side` enum('Front','Left','Right','Back','Open') NOT NULL default 'Front',
  `item_image_url` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL default '999990',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `demonstration_item_id` (`item_id`,`item_image_side`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_image
#----------------------------


insert  into item_image values 
(1, 1, 'Front', 'offgrid_door_closed.png', 10), 
(4, 4, 'Front', 'cokelife_ws.png', 10), 
(30, 2, 'Front', 'dasani_ws.png', 10), 
(7, 1, 'Left', 'offgrid_door_side.png', 20), 
(8, 1, 'Open', 'offgrid_door_open.png', 30), 
(23, 19, 'Front', 'melloyello_ws.png', 10), 
(31, 25, 'Front', 'dasani_ws.png', 10), 
(11, 7, 'Front', 'coolio_ws.png', 10), 
(12, 8, 'Front', 'flatpack_ws.png', 10), 
(13, 10, 'Front', 'Glacier_ws.png', 10), 
(14, 11, 'Front', 'splashbar_ws.png', 10), 
(15, 12, 'Front', 'Superchill_ws.png', 10), 
(16, 13, 'Front', 'offgrid_door_closed.png', 10), 
(17, 14, 'Front', 'offgrid_door_closed.png', 10), 
(18, 15, 'Front', 'offgrid_door_closed.png', 10), 
(19, 16, 'Front', 'offgrid_door_closed.png', 10), 
(20, 17, 'Front', 'offgrid_door_closed.png', 10), 
(21, 18, 'Front', 'offgrid_door_closed.png', 10), 
(22, 0, '', '', 0), 
(33, 27, 'Front', 'dasani_ws.png', 10), 
(32, 26, 'Front', 'dasani_ws.png', 10), 
(24, 20, 'Front', 'coke12_ws.png', 10), 
(25, 11, 'Open', 'test_0360.png', 20), 
(26, 21, 'Front', 'dasani_ws.png', 10), 
(29, 24, 'Front', 'dasani6_ws.png', 10), 
(27, 22, 'Front', 'sprite_ws.png', 10), 
(28, 23, 'Front', 'surge_ws.png', 10), 
(34, 28, 'Front', 'dasani_ws.png', 10), 
(35, 29, 'Front', 'dasani_ws.png', 10);
#----------------------------
# Table structure for item_image_highlight
#----------------------------
CREATE TABLE `item_image_highlight` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_image_id` int(10) unsigned NOT NULL default '0',
  `hotspot_left` int(11) NOT NULL default '0',
  `hotspot_top` int(11) NOT NULL default '0',
  `item_image_highlight_info` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_image_highlight
#----------------------------


insert  into item_image_highlight values 
(1, 1, 100, 200, 'This is the popup highlight info text for the first image.'), 
(2, 1, 500, 300, 'This is another hightlight text positioned at 500 left, 300 top.<br />This is a second paragraph.'), 
(5, 1, 100, 600, 'Top 600, left 100.<br /><br />Two carriage returns above.<br /><br />Two carriage returns again.');
#----------------------------
# Table structure for item_info
#----------------------------
CREATE TABLE `item_info` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_info_type_id` int(10) unsigned NOT NULL default '0',
  `item_info` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `demonstration_item_id` (`item_id`,`item_info_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_info
#----------------------------


insert  into item_info values 
(1, 1, 1, '<p class=\"p1\">Operates without being plugged in, new direction for future equipment design, highlights potential use of hydrogen fuel cell power for TCCC equipment.</p>\r\n<p class=\"p1\"><strong>Availability:</strong><br />Prototypes available for demo purposes only&nbsp;</p>'), 
(2, 1, 2, '<p class=\"p1\">Designed for use with a hydrogen fuel cell system, the cooler and vendor&nbsp;will each run for 8+ hours off the grid/without a recharge.</p>\r\n<p class=\"p1\"><strong>Weight:<br /></strong>600lbs (cooler), 900lbs (vendor)<strong><br /></strong></p>\r\n<p class=\"p1\"><strong>Power Source:</strong><br />(2) 120v, 60hz<br /><br /><br /></p>'), 
(3, 1, 3, '<p class=\"p1\">No&nbsp;wall plug needed, presents future technology (hydrogen fuel cell power)!</p>'), 
(4, 7, 1, '<p>Something about something</p>'), 
(5, 7, 2, '<p>The second whatever thing.</p>'), 
(6, 7, 3, '<p>The third thing is here!</p>'), 
(8, 1, 4, '<p>Design type info. This is useful info of the type design type.</p>'), 
(9, 7, 4, '<p>Yo, designers.</p>\r\n<p>Yikes, designers.</p>');
#----------------------------
# Table structure for item_info_image
#----------------------------
CREATE TABLE `item_info_image` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_info_image_url` varchar(255) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_info_image
#----------------------------


insert  into item_info_image values 
(14, 1, 'off-grid_bottom-01.jpg', 20), 
(21, 1, 'Off Grid Project, Presentation 10-15-20148.jpg', 30), 
(5, 7, 'test-img-1.png', 10), 
(18, 1, 'off-grid_tanks-02-01.jpg', 10), 
(12, 1, 'og_pres_Page_13.jpg', 40);
#----------------------------
# Table structure for item_info_type
#----------------------------
CREATE TABLE `item_info_type` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_info_type_name` varchar(24) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_info_type
#----------------------------


insert  into item_info_type values 
(1, 'About'), 
(2, 'Specs'), 
(3, 'Technology'), 
(4, 'Design');
#----------------------------
# Table structure for item_presentation
#----------------------------
CREATE TABLE `item_presentation` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_presentation_name` varchar(255) NOT NULL,
  `item_presentation_thumbnail_url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_presentation
#----------------------------


insert  into item_presentation values 
(1, 1, 'Bird Photos', 'show001.thumb.png'), 
(2, 1, 'Furniture', 'show001.thumb.png'), 
(11, 1, 'Presentation 5', 'og_pres_Page_12.jpg'), 
(10, 1, 'Off Grid Cooler', 'og_pres_Page_02.jpg'), 
(6, 1, 'Off Grid Cooler', 'og_pres_Page_01.jpg');
#----------------------------
# Table structure for item_presentation_image
#----------------------------
CREATE TABLE `item_presentation_image` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_presentation_id` int(10) unsigned NOT NULL,
  `item_presentation_image_url` varchar(255) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_presentation_image
#----------------------------


insert  into item_presentation_image values 
(1, 1, 'cattle_egret.jpg', 20), 
(2, 1, 'dunlins.jpg', 30), 
(3, 2, 'old_wall.jpg', 30), 
(4, 2, 'ones_zeros.jpg', 40), 
(9, 3, 'old_wall.jpg', 20), 
(8, 3, 'cattle_egret.jpg', 10), 
(10, 4, 'ones_zeros.jpg', 10), 
(11, 4, 'old_wall.jpg', 20), 
(12, 5, '', 20), 
(13, 5, '', 10), 
(23, 10, 'og_pres_Page_09.jpg', 30), 
(16, 6, 'Slide18.jpg', 30), 
(21, 6, 'Slide12.jpg', 20), 
(22, 6, 'Slide09.jpg', 10), 
(24, 10, 'og_pres_Page_11.jpg', 20), 
(25, 10, 'og_pres_Page_12.jpg', 10), 
(26, 11, 'og_pres_Page_04.jpg', 20), 
(28, 11, 'og_pres_Page_03.jpg', 10);
#----------------------------
# Table structure for item_video
#----------------------------
CREATE TABLE `item_video` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_video_title` varchar(255) NOT NULL,
  `item_video_url` varchar(255) NOT NULL,
  `item_video_placeholder_image_url` varchar(255) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table item_video
#----------------------------


insert  into item_video values 
(1, 1, 'LED Cooler', '0410 LED Cooler_1.mp4', 'litetubevid.jpg', 20), 
(2, 1, 'Open that Door', 'WP_20150115_008.mp4', 'litetubevid.jpg', 30), 
(6, 7, 'Test Video', 'WP_20150115_008.mp4', '', 10), 
(13, 1, 'Test  Vid Jay 7', 'PowerCool SplashBar_SD.wmv', '', 10);
#----------------------------
# Table structure for light
#----------------------------
CREATE TABLE `light` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `overview_text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table light
#----------------------------


insert  into light values 
(1, 1, 'Light: sdad asd lsad khidf hdkfh sdkfh sdkfhih sdkhdf uigif rgohgou9g aadjgdf fdglfg si8d di7'), 
(2, 2, 'Packaging Light'), 
(3, 3, '');
#----------------------------
# Table structure for light_image
#----------------------------
CREATE TABLE `light_image` (
  `id` int(11) NOT NULL auto_increment,
  `light_id` int(11) NOT NULL,
  `image_file` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table light_image
#----------------------------


insert  into light_image values 
(1, 1, 'tech-featured-image.png', 10), 
(2, 1, 'tech-featured-image.png', 20);
#----------------------------
# Table structure for light_test
#----------------------------
CREATE TABLE `light_test` (
  `id` int(11) NOT NULL auto_increment,
  `light_id` int(11) default NULL,
  `dark_text` varchar(255) default NULL,
  `light_text` varchar(255) default NULL,
  `image_file_dark` varchar(100) default NULL,
  `image_file_light` varchar(100) default NULL,
  `background_image_file` varchar(100) default NULL,
  `rgba_value` varchar(30) default NULL,
  `rgba_value_right` varchar(30) default NULL,
  `sort_order` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table light_test
#----------------------------


insert  into light_test values 
(1, 1, 'Dark - Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Light - Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'light-dark.png', 'light-dark.png', '00044CFNoRibbonDry.JPG', '235, 247, 254, .90', '235, 247, 254, 0.2', 10);
#----------------------------
# Table structure for patent
#----------------------------
CREATE TABLE `patent` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `patent_name` varchar(32) NOT NULL,
  `patent_image_url` varchar(255) NOT NULL,
  `patent_abstract` text NOT NULL,
  `patent_probable_assignee` text NOT NULL,
  `patent_assignees_std` text NOT NULL,
  `patent_assignees` text NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table patent
#----------------------------


insert  into patent values 
(1, 1, 'Beverage Dispenser', 'intellectual-property-img.png', 'Source: USD731841S [Source: Claim 1] The ornamental design for a beverage dispenser, as shown and described.', 'COCA COLA CO', 'COCA COLA CO', 'FORPEOPLE LTD', 10), 
(3, 1, 'Patent II', 'intellectual-property-img-2.png', '', '', '', '', 20);
#----------------------------
# Table structure for setting
#----------------------------
CREATE TABLE `setting` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `setting_name` varchar(32) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table setting
#----------------------------


insert  into setting values 
(1, 'Screen Saver', 'Fish in bowl'), 
(2, 'How to Screen Share', 'Get some good soup, then eat it. Have a good time.'), 
(3, 'Back End Login', 'Here\'s some info about back end login. Very interesting info.'), 
(4, 'Main Headline', '');
#----------------------------
# Table structure for sound
#----------------------------
CREATE TABLE `sound` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `overview_text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table sound
#----------------------------


insert  into sound values 
(1, 1, 'Sound: sdad asd lsad khidf hdkfh sdkfh sdkfhih sdkhdf uigif rgohgou9g aadjgdf fdglfg si8d di7'), 
(2, 2, ''), 
(3, 3, '');
#----------------------------
# Table structure for sound_image
#----------------------------
CREATE TABLE `sound_image` (
  `id` int(11) NOT NULL auto_increment,
  `sound_id` int(11) NOT NULL,
  `image_file` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table sound_image
#----------------------------


insert  into sound_image values 
(1, 1, 'tech-featured-image.png', 10), 
(2, 1, 'tech-featured-image.png', 20);
#----------------------------
# Table structure for sound_test
#----------------------------
CREATE TABLE `sound_test` (
  `id` int(11) NOT NULL auto_increment,
  `sound_id` int(11) default NULL,
  `sound_test_description` text,
  `sound_url1` varchar(255) default NULL,
  `sound_url2` varchar(255) default NULL,
  `sound_url3` varchar(255) default NULL,
  `image_url1` varchar(100) default NULL,
  `image_url2` varchar(100) default NULL,
  `image_url3` varchar(100) default NULL,
  `text1` char(6) NOT NULL,
  `text2` char(6) NOT NULL,
  `text3` char(6) NOT NULL,
  `sort_order` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table sound_test
#----------------------------


insert  into sound_test values 
(1, 1, 'Sound - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut felis non elit vehicula posuere. Integer pretium libero sit amet felis tincidunt, sit amet dictum nibh vulputate.', 'Imbera G319 Blun_120v60hz_Co2_ALL On_002_DUT Data_A1_ N74.wav', 'Imbera G319 Co2 Blunt_120Vac_3M Alum on Condenser coil_thinsulate walls_Thinsulate rear grill exp_002_DUT Data_A1_ N74.wav', 'Sanden Gen 2 Quiet Compressor_120V60Hz_Co2_All On_003_DUT Data_A1_ N74.wav', 'pod-img.png', 'pod-img.png', 'pod-img.png', '65db', '55db', '35db', 10);
#----------------------------
# Table structure for system_user
#----------------------------
CREATE TABLE `system_user` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `is_admin` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table system_user
#----------------------------


insert  into `system_user` values 
(1, 'Jay', 'Davis', 'jdavis', 'baseball', 'jay.davis@phase3mc.com', '2015-12-03 00:00:00', 1, 1), 
(2, 'Admin', 'User', 'admin', 'r3@lthang', '', '0000-00-00 00:00:00', 1, 1);
#----------------------------
# Table structure for technology
#----------------------------
CREATE TABLE `technology` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `technology_name` varchar(32) NOT NULL,
  `technology_button_image_url` varchar(100) NOT NULL,
  `technology_button_active_image_url` varchar(100) NOT NULL,
  `link_url` varchar(100) NOT NULL default 'tech-standard.php',
  `technology_headline` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL default '1',
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table technology
#----------------------------


insert  into technology values 
(1, 1, 'Audio Reduction', 'tech-sound.png', 'tech-sound-active.png', 'tech-sound.php', 'Can you hear the difference?', 1, 50), 
(2, 1, 'iBeacon', 'tech-ibeacon.png', 'tech-ibeacon-active.png', 'tech-standard.php', 'Machines Can Talk!', 1, 60), 
(3, 1, 'Light', 'tech-light.png', 'tech-light-active.png', 'tech-light.php', 'Can you see the light?', 1, 40), 
(5, 1, 'Color', 'tech-color.png', 'tech-color-active.png', 'tech-standard.php', 'Color Fade Resistant Equipment', 1, 140), 
(15, 3, 'Heat 1', 'color.png', '', 'tech-standard.php', 'Heat 2', 0, 20), 
(7, 2, 'Audio Reduction', 'tech-sound.png', 'tech-sound-active.png', 'tech-sound.php', 'Can you hear the difference?', 1, 70), 
(8, 2, 'iBeacon', 'tech-ibeacon.png', 'tech-ibeacon-active.png', 'tech-standard.php', 'Machines Can Talk!', 1, 90), 
(9, 2, 'Light', 'tech-light.png', 'tech-light-active.png', 'tech-light.php', 'Can you see the light?', 1, 100), 
(10, 2, 'Color', 'tech-color.png', 'tech-color-active.png', 'tech-standard.php', 'Color Fade Resistant Equipment', 1, 120), 
(11, 3, 'Audio Reduction', 'tech-sound.png', 'tech-sound-active.png', 'tech-sound.php', 'Can you hear the difference?', 1, 30), 
(12, 3, 'iBeacon', 'tech-ibeacon.png', 'tech-ibeacon-active.png', 'tech-standard.php', 'Machines Can Talk!', 1, 80), 
(13, 3, 'Light', 'tech-light.png', 'tech-light-active.png', 'tech-light.php', 'Can you see the light?', 1, 110), 
(14, 3, 'Color', 'tech-color.png', 'tech-color-active.png', 'tech-standard.php', 'Color Fade Resistant Equipment', 1, 130), 
(16, 1, 'Heat', 'color.png', '', 'tech-standard.php', 'Heat', 0, 10);
#----------------------------
# Table structure for technology_info
#----------------------------
CREATE TABLE `technology_info` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `technology_id` int(10) unsigned NOT NULL default '0',
  `technology_info_name` varchar(32) NOT NULL,
  `technology_info_description` text NOT NULL,
  `technology_info_button_image_url` varchar(255) NOT NULL,
  `technology_info_template` enum('standard','sound','lighting') NOT NULL default 'standard',
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table technology_info
#----------------------------


insert  into technology_info values 
(1, 1, 'Type Stuff', 'Yolo', '', 'standard', 10), 
(8, 14, 'Color', 'This is about Color', '', 'standard', 10), 
(3, 1, 'George', '', 'instagram-icon.png', 'sound', 20), 
(4, 5, 'Color', 'Color - sdad asd lsad khidf hdkfh sdkfh sdkfhih sdkhdf uigif rgohgou9g aadjgdf fdglfg si8d di7', '', 'standard', 30), 
(5, 2, 'Overview', 'iBeacon stuff', '', 'standard', 40), 
(7, 6, 'Overview', '', '', 'standard', 10), 
(9, 15, 'Heat Testing', 'A blurb about heat', '', 'standard', 10), 
(11, 16, 'Heat Testing', 'Our machines can stand the heat of the sun.', '', 'standard', 10);
#----------------------------
# Table structure for technology_info_image
#----------------------------
CREATE TABLE `technology_info_image` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `technology_info_id` int(10) unsigned NOT NULL default '0',
  `technology_info_image_url` varchar(255) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
#----------------------------
# Records for table technology_info_image
#----------------------------


insert  into technology_info_image values 
(1, 3, 'twitter-icon.png', 9990), 
(3, 4, 'tech-featured-image.png', 10), 
(4, 4, 'tech-featured-image.png', 20), 
(5, 5, 'tech-featured-image.png', 10), 
(6, 5, 'tech-featured-image.png', 20), 
(7, 5, 'tech-featured-image.png', 30), 
(11, 9, 'adept.full_ajd15-i301-0481.jpg', 0), 
(10, 8, 'tech-featured-image.png', 0), 
(12, 11, 'adept.full_ajd15-i301-0481.jpg', 0);
#----------------------------
# Table structure for technology_info_recording
#----------------------------
CREATE TABLE `technology_info_recording` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `technology_info_id` int(10) unsigned NOT NULL default '0',
  `technology_info_recording_url` varchar(255) NOT NULL,
  `sort_order` smallint(6) NOT NULL default '9990',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
#----------------------------
# No records for table technology_info_recording
#----------------------------

