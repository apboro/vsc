export default [
    {
        title: 'НАСТРОЙКИ', route: '', //permission: ['dictionaries.edit'],
        items: [
            {title: 'Сотрудники', route: 'staff-list', permission: ['staff.view','staff.edit','staff.delete','staff.access','staff.permissions']},
            {title: 'Объекты', route: 'training-base-list', permission: ['training_base.view','training_base.edit','training_base.delete']},
            {title: 'Справочники', route: 'dictionaries', permission: 'dictionaries.edit'},
            // {title: 'Роли', route: 'roles-list', permission: 'roles.edit'},
            // {title: 'Настройки', route: 'settings'},
        ]
    },
];
