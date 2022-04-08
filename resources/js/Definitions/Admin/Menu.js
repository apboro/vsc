export default [
    // {
    //     'title': 'ПРИЧАЛЫ И РЕЙСЫ', 'route': '',
    //     'items': [
    //         {'title': 'Рейсы', 'route': 'trip-list'},
    //         {'title': 'Причалы', 'route': 'pier-list'},
    //     ]
    // },
    {
        title: 'НАСТРОЙКИ', route: '', //permission: ['dictionaries.edit'],
        items: [
            {title: 'Сотрудники', route: 'staff-list', permission: ['staff.view','staff.edit','staff.delete','staff.access','staff.permissions']},
            {title: 'Справочники', route: 'dictionaries', permission: 'dictionaries.edit'},
            {title: 'Роли', route: 'roles-list', permission: 'roles.edit'},
            // {title: 'Настройки', route: 'settings'},
        ]
    },
];
