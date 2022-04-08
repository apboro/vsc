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

export default [
    {path: '/', name: 'home', component: TasksListPage, meta: {title: 'Список задач'}},
    {path: '/tasks', name: 'tasks-list', component: TasksListPage, meta: {title: 'Список задач'}},

    {path: '/dictionaries', name: 'dictionaries', component: DictionariesPage, meta: {title: 'Справочники', permission: 'dictionaries.edit'}},

    // {path: '/roles', name: 'roles-list', component: RolesPage, meta: {title: 'Роли', permission: 'roles.edit'}},
    // {path: '/roles/:id', name: 'roles-edit', component: RolesEditPage, meta: {title: 'Роль', permission: 'roles.edit'}},

    // {path: '/settings', name: 'settings', component: SettingsPage, meta: {title: 'Настройки'}},

    {path: '/staff', name: 'staff-list', component: StaffListPage, meta: {title: 'Список сотрудников', permission: ['staff.view','staff.edit','staff.delete','staff.access','staff.permissions']}},
    {path: '/staff/:id', name: 'staff-view', component: StaffViewPage, meta: {title: 'Карточка сотрудника', permission: ['staff.view','staff.edit','staff.delete','staff.access','staff.permissions']}},
    {path: '/staff/:id/edit', name: 'staff-edit', component: StaffEditPage, meta: {title: 'Редактирование карточки сотрудника', permission: 'staff.edit'}},

    {path: '/403', name: '403', component: Forbidden},
    {path: '/:pathMatch(.*)*', name: '404', component: NotFound},
];
