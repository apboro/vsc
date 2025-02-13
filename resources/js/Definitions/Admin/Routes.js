import NotFound from '@/Pages/NotFound';
import TasksListPage from "@/Pages/Admin/Tasks/TasksListPage";
import DictionariesPage from "@/Pages/Admin/Dictionaries/DictionariesPage";
import StaffListPage from "@/Pages/Admin/Staff/StaffListPage";
import StaffViewPage from "@/Pages/Admin/Staff/StaffViewPage";
import StaffEditPage from "@/Pages/Admin/Staff/StaffEditPage";
// import RolesPage from "@/Pages/Admin/Roles/RolesPage";
// import RolesEditPage from "@/Pages/Admin/Roles/RolesEditPage";
// import SettingsPage from "@/Pages/Admin/Settings/SettingsPage";
import Forbidden from "@/Pages/Forbidden";
import TrainingBaseListPage from "@/Pages/Admin/TrainingBase/TrainingBaseListPage";
import TrainingBaseEditPage from "@/Pages/Admin/TrainingBase/TrainingBaseEditPage";
import TrainingBaseViewPage from "@/Pages/Admin/TrainingBase/TrainingBaseViewPage";
import OrganizationsListPage from "@/Pages/Admin/Organizations/OrganizationsListPage";
import OrganizationsViewPage from "@/Pages/Admin/Organizations/OrganizationsViewPage";
import OrganizationsEditPage from "@/Pages/Admin/Organizations/OrganizationsEditPage";
import ServicesListPage from "@/Pages/Admin/Services/ServicesListPage";
import ServicesEditPage from "@/Pages/Admin/Services/ServicesEditPage";
import ServicesViewPage from "@/Pages/Admin/Services/ServicesViewPage";
import LeadsListPage from "@/Pages/Admin/Leads/LeadsListPage";
import LeadsViewPage from "@/Pages/Admin/Leads/LeadsViewPage";
import ClientsListPage from "@/Pages/Admin/Clients/ClientsListPage";
import ClientsViewPage from "@/Pages/Admin/Clients/ClientsViewPage";
import SubscriptionsListPage from "@/Pages/Admin/Subscriptions/SubscriptionsListPage";
import SubscriptionsViewPage from "@/Pages/Admin/Subscriptions/SubscriptionsViewPage";
import ClientSubscriptionsViewPage from "@/Pages/Admin/Clients/ClientSubscriptionsViewPage";

export default [
    {path: '/', name: 'home', component: TasksListPage, meta: {title: 'Список задач'}},
    {path: '/tasks', name: 'tasks-list', component: TasksListPage, meta: {title: 'Список задач'}},

    {path: '/dictionaries', name: 'dictionaries', component: DictionariesPage, meta: {title: 'Справочники', permission: 'dictionaries.edit'}},

    // {path: '/roles', name: 'roles-list', component: RolesPage, meta: {title: 'Роли', permission: 'roles.edit'}},
    // {path: '/roles/:id', name: 'roles-edit', component: RolesEditPage, meta: {title: 'Роль', permission: 'roles.edit'}},

    // {path: '/settings', name: 'settings', component: SettingsPage, meta: {title: 'Настройки'}},

    {path: '/staff', name: 'staff-list', component: StaffListPage, meta: {title: 'Список сотрудников', permission: ['staff.view','staff.edit','staff.delete','staff.access','staff.permissions']}},
    {path: '/staff/:id', name: 'staff-view', component: StaffViewPage, meta: {title: 'Карточка сотрудника', permission: ['staff.view','staff.edit','staff.delete','staff.access','staff.permissions'], change: 'staff-list'}},
    {path: '/staff/:id/edit', name: 'staff-edit', component: StaffEditPage, meta: {title: 'Редактирование карточки сотрудника', permission: 'staff.edit', change: 'staff-list'}},

    {path: '/bases', name: 'training-base-list', component: TrainingBaseListPage, meta: {title: 'Список объектов', permission: ['training_base.view','training_base.edit','training_base.delete']}},
    {path: '/bases/:id', name: 'training-base-view', component: TrainingBaseViewPage, meta: {title: 'Карточка объекта', permission: ['training_base.view','training_base.edit','training_base.delete'], change: 'training-base-list'}},
    {path: '/bases/:id/edit', name: 'training-base-edit', component: TrainingBaseEditPage, meta: {title: 'Редактирование объекта', permission: 'training_base.edit', change: 'training-base-list'}},

    {path: '/organizations', name: 'organizations-list', component: OrganizationsListPage, meta: {title: 'Организации', role: 'super'}},
    {path: '/organizations/:id', name: 'organizations-view', component: OrganizationsViewPage, meta: {title: 'Карточка организации', role: 'super'}},
    {path: '/organizations/:id/edit', name: 'organizations-edit', component: OrganizationsEditPage, meta: {title: 'Редактирование организации', role: 'super'}},

    {path: '/services', name: 'services-list', component: ServicesListPage, meta: {title: 'Список услуг', permission: ['services.view','services.edit','services.delete']}},
    {path: '/services/:id', name: 'services-view', component: ServicesViewPage, meta: {title: 'Карточка услуги', permission: ['services.view','services.edit','services.delete'], change: 'services-list'}},
    {path: '/services/:id/edit', name: 'services-edit', component: ServicesEditPage, meta: {title: 'Редактирование услуги', permission: 'services.edit', change: 'services-list'}},
    {path: '/services/:id/copy/:newId', name: 'services-copy', component: ServicesEditPage, meta: {title: 'Копирование услуги', permission: 'services.edit', change: 'services-list'}},

    {path: '/leads', name: 'leads-list', component: LeadsListPage, meta: {title: 'Список лидов', permission: ['leads.view','leads.register']}},
    {path: '/leads/:id', name: 'leads-view', component: LeadsViewPage, meta: {title: 'Лид', change: 'leads-list', permission: ['leads.view','leads.register']}},

    {path: '/clients', name: 'clients-list', component: ClientsListPage, meta: {title: 'Клиенты', permission: ['clients.view']}},
    {path: '/clients/:id', name: 'clients-view', component: ClientsViewPage, meta: {title: 'Клиент', change: 'clients-list', permission: ['clients.view']}},
    {path: '/clients/:client/subscription/:id', name: 'clients-subscriptions-view', component: ClientSubscriptionsViewPage, meta: {title: 'Подписка на услугу', change: 'clients-list', permission: ['clients.view']}},

    {path: '/subscriptions', name: 'subscriptions-list', component: SubscriptionsListPage, meta: {title: 'Подписки на услуги', permission: ['subscriptions.view']}},
    {path: '/subscriptions/:id', name: 'subscriptions-view', component: SubscriptionsViewPage, meta: {title: 'Подписка на услугу', change: 'subscriptions-list', permission: ['subscriptions.view']}},

    {path: '/403', name: '403', component: Forbidden},
    {path: '/:pathMatch(.*)*', name: '404', component: NotFound},
];
