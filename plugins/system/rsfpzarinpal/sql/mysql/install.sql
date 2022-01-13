INSERT IGNORE INTO `#__rsform_config` (`SettingName`, `SettingValue`) VALUES
('zarinpal.merchant', ''),
('zarinpal.currency', ''),
('zarinpal.test', '0');

DELETE FROM `#__rsform_component_types` WHERE `ComponentTypeId` IN (606);

INSERT IGNORE INTO `#__rsform_component_types` (`ComponentTypeId`, `ComponentTypeName`, `CanBeDuplicated`) VALUES
(606, 'zarinpal', 0);

DELETE FROM `#__rsform_component_type_fields` WHERE ComponentTypeId = 606;
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES
(606, 'NAME', 'textbox', '', 0),
(606, 'LABEL', 'textbox', '', 1),
(606, 'COMPONENTTYPE', 'hidden', '606', 2),
(606, 'LAYOUTHIDDEN', 'hiddenparam', 'YES', 7);