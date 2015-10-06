
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_cars` AS select distinct `catalog`.`family` AS `family`,'hyundai' AS `marka` from `catalog` order by `catalog`.`family`;

-- CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_catalogs` AS select 'region' AS `type_code`,'EN' AS `lang_code`,'Регион' AS `name`,`catalog`.`catalogue_code` AS `cat_code`,group_concat(replace(trim(both '|' from `catalog`.`data_regions`),'|',';') separator ';') AS `value` from `catalog` group by `catalog`.`catalogue_code` union select 'YY' AS `type_code`,'EN' AS `lang_code`,'Год выпуска' AS `name`,`catalog`.`catalogue_code` AS `cat_code`,`years_interval`(`catalog`.`from_year`,`catalog`.`to_year`) AS `value` from `catalog` union select `ucctype`.`ucc_type` AS `type_code`,`lex_def`.`lang_code` AS `lang_code`,`lex_def`.`lex_desc` AS `name`,`ucctype`.`catalogue_code` AS `cat_code`,trim(both ';' from (select group_concat(distinct `ucc_lex_def`.`lex_desc` separator ';') AS `ucc_lex_desc` from (`cats0_ucc` `ucc` left join `lex_lex` `ucc_lex_def` on(((`ucc_lex_def`.`lang_code` = 'EN') and (`ucc`.`lex_code1` = `ucc_lex_def`.`lex_code`)))) where ((`ucc`.`catalogue_code` = `ucctype`.`catalogue_code`) and (`ucc`.`ucc_type` = `ucctype`.`ucc_type`)) group by `ucc`.`catalogue_code`)) AS `value` from ((((`cats0_ucctype` `ucctype` left join `lex_lex` `lex_def` on(((`lex_def`.`lang_code` = 'EN') and (`ucctype`.`lex_code` = `lex_def`.`lex_code`)))) left join `lex_lex` `lex_loc` on(((`lex_loc`.`lang_code` = 'RU') and (`ucctype`.`lex_code` = `lex_loc`.`lex_code`)))) left join `lex_lex` `lex_qual_def` on(((`lex_qual_def`.`lang_code` = 'QE') and (`ucctype`.`lex_code` = `lex_qual_def`.`lex_code`)))) left join `lex_lex` `lex_qual_loc` on(((`lex_qual_loc`.`lang_code` = 'QR') and (`ucctype`.`lex_code` = `lex_qual_loc`.`lex_code`))));

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_catalogs` AS select 'region' AS `type_code`,'EN' AS `lang_code`,'Регион' AS `name`,`catalog`.`catalogue_code` AS `cat_code`,group_concat(replace(trim(both '|' from `catalog`.`data_regions`),'|',';') separator ';') AS `value` from `catalog` group by `catalog`.`catalogue_code` union select 'YY' AS `type_code`,'EN' AS `lang_code`,'Год выпуска' AS `name`,`catalog`.`catalogue_code` AS `cat_code`,`years_interval`(`catalog`.`from_year`,`catalog`.`to_year`) AS `value` from `catalog` union select `ucctype`.`ucc_type` AS `type_code`,`lex_def`.`lang_code` AS `lang_code`,`lex_def`.`lex_desc` AS `name`,`ucctype`.`catalogue_code` AS `cat_code`,trim(both ';' from (select group_concat(distinct `ucc_lex_def`.`lex_desc` separator ';') AS `ucc_lex_desc` from (`cats0_ucc` `ucc` left join `lex_lex` `ucc_lex_def` on(((`ucc_lex_def`.`lang_code` = 'EN') and (`ucc`.`lex_code1` = `ucc_lex_def`.`lex_code`)))) where ((`ucc`.`catalogue_code` = `ucctype`.`catalogue_code`) and (`ucc`.`ucc_type` = `ucctype`.`ucc_type`)) group by `ucc`.`catalogue_code`)) AS `value` from (`cats0_ucctype` `ucctype` left join `lex_lex` `lex_def` on(((`lex_def`.`lang_code` = 'EN') and (`ucctype`.`lex_code` = `lex_def`.`lex_code`))));

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_models` AS select distinct `catalog`.`catalogue_code` AS `cat_code`,`catalog`.`cat_name` AS `cat_name`,`catalog`.`family` AS `family`,cast(`catalog`.`production_from` as date) AS `from`,cast(`catalog`.`production_to` as date) AS `to`,'hyundai' AS `marka`,`catalog`.`data_regions` AS `region` from `catalog` order by `catalog`.`production_from` desc,`catalog`.`production_to` desc;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_catalog_info` AS select `catalog`.`catalogue_code` AS `cat_code`,`catalog`.`cat_name` AS `cat_name`,`catalog`.`cat_number` AS `cat_number`,`catalog`.`family` AS `family`,`catalog`.`from_year` AS `from_year`,`catalog`.`to_year` AS `to_year`,`catalog`.`production_from` AS `from`,`catalog`.`production_to` AS `to`,`catalog`.`data_format_id` AS `data_format_id`,`catalog`.`make_id` AS `make_id`,`catalog`.`make_description` AS `make_description`,`catalog`.`catalogue_type` AS `catalogue_type`,`catalog`.`menu_categories` AS `menu_categories`,`catalog`.`major_division` AS `major_division`,`catalog`.`group_type` AS `group_type`,`catalog`.`car_gb` AS `car_gb`,`catalog`.`pc_gb` AS `pc_gb`,`catalog`.`search_fields` AS `search_fields`,`catalog`.`cat_folder` AS `cat_folder`,`catalog`.`cutup_code` AS `cutup_code`,`catalog`.`data_regions` AS `data_regions`,`catalog`.`previous_cat` AS `previous_cat`,`catalog`.`previous_cat_code` AS `previous_cat_code`,`catalog`.`previous_region` AS `previous_region`,`catalog`.`include_countries` AS `include_countries`,`catalog`.`excluded_countries` AS `excluded_countries`,`cats0_catalog`.`production_from` AS `c0_production_from`,`cats0_catalog`.`production_to` AS `c0_production_to`,`cats0_catalog`.`vehicle_type_code` AS `vehicle_type_code`,`cats0_catalog`.`vehicle_type` AS `vehicle_type`,`cats0_catalog`.`year_from` AS `year_from`,`cats0_catalog`.`year_to` AS `year_to`,coalesce(`lex_qual_loc`.`lang_code`,`lex_loc`.`lang_code`,`lex_qual_def`.`lang_code`,`lex_def`.`lang_code`) AS `lang_code`,coalesce(`lex_qual_loc`.`lex_desc`,`lex_loc`.`lex_desc`,`lex_qual_def`.`lex_desc`,`lex_def`.`lex_desc`) AS `lex_desc`,'hyundai' AS `marka` from (((((`catalog` join `cats0_catalog` on((`catalog`.`catalogue_code` = `cats0_catalog`.`catalogue_code`))) left join `lex_lex` `lex_def` on(((`lex_def`.`lang_code` = 'EN') and (`cats0_catalog`.`cat_name_lex_code` = `lex_def`.`lex_code`)))) left join `lex_lex` `lex_loc` on(((`lex_loc`.`lang_code` = 'RU') and (`cats0_catalog`.`cat_name_lex_code` = `lex_loc`.`lex_code`)))) left join `lex_lex` `lex_qual_def` on(((`lex_qual_def`.`lang_code` = 'QE') and (`cats0_catalog`.`cat_name_lex_code` = `lex_qual_def`.`lex_code`)))) left join `lex_lex` `lex_qual_loc` on(((`lex_qual_loc`.`lang_code` = 'QR') and (`cats0_catalog`.`cat_name_lex_code` = `lex_qual_loc`.`lex_code`))));


DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `years_interval`(a_from int, a_to int) RETURNS text CHARSET utf8
BEGIN
declare s varchar(250);
declare y int;
set s=a_from;
set y=a_from+1;
-- set a_to=2015;
if (a_to=0)
then
set a_to=year(now());
else
set a_to=a_to;
end if;
while (y<a_to+1) do
 set s=concat(s,';',y);
set y=y+1;
end while;
RETURN s;
END$$
DELIMITER ;
