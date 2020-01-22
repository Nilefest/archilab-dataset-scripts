CREATE TABLE `filter_country` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `country_ru` varchar(250) NOT NULL,
  `country_en` varchar(250) NOT NULL,
  `country_html_id` varchar(250) NOT NULL,
  `digital_ci` varchar(250) NOT NULL DEFAULT '0',
  `global_ci` varchar(250) NOT NULL DEFAULT '0',
  `innovation_i` varchar(250) NOT NULL DEFAULT '0',
  `human_di` varchar(250) NOT NULL DEFAULT '0',
  `gdp` varchar(250) NOT NULL DEFAULT '0',
  `eg_rate` varchar(250) NOT NULL DEFAULT '0',
  `gdp_person` varchar(250) NOT NULL DEFAULT '0',
  `quality_l` varchar(250) NOT NULL DEFAULT '0',
  `happy_i` varchar(250) NOT NULL DEFAULT '0',
  `solid_gi` varchar(250) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `filter_country`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `filter_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;