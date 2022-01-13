DELETE FROM #__rsform_config WHERE SettingName = 'zarinpal.email';
DELETE FROM #__rsform_config WHERE SettingName = 'zarinpal.currency';
DELETE FROM #__rsform_config WHERE SettingName = 'zarinpal.test';

DELETE FROM #__rsform_component_types WHERE ComponentTypeId = 606;
DELETE FROM #__rsform_component_type_fields WHERE ComponentTypeId = 606;
