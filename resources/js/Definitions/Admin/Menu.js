export default [
    {
        title: 'КЛИЕНТЫ', items: [
            {title: 'Лиды', route: 'leads-list', permission: ['leads.view', 'leads.register']},
            {title: 'Клиенты', route: 'clients-list', permission: ['clients.view']},
            {title: 'Подписки на услуги', route: 'subscriptions-list', permission: ['subscriptions.view']},
        ],
        permission: ['leads.view', 'leads.register', 'clients.view', 'subscriptions.view']
    },
    {title: 'УСЛУГИ', route: 'services-list', permission: ['services.view', 'services.edit', 'services.delete']},

    {title: 'ОБЪЕКТЫ', route: 'training-base-list', permission: ['training_base.view', 'training_base.edit', 'training_base.delete']},
    {title: 'СОТРУДНИКИ', route: 'staff-list', permission: ['staff.view', 'staff.edit', 'staff.delete', 'staff.access', 'staff.permissions']},
    {
        title: 'НАСТРОЙКИ', route: '', //permission: ['dictionaries.edit'],
        items: [
            {title: 'Организации', route: 'organizations-list', role: 'super'},
            {title: 'Справочники', route: 'dictionaries', permission: 'dictionaries.edit'},
            // {title: 'Роли', route: 'roles-list', permission: 'roles.edit'},
            // {title: 'Настройки', route: 'settings'},
        ]
    },
];
