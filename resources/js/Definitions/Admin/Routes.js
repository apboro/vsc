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

    {path: '/bases', name: 'training-base-list', component: TrainingBaseListPage, meta: {title: 'Список объектов', permission: ['training_base.view','training_base.edit','training_base.delete']}},
    {path: '/bases/:id', name: 'training-base-view', component: TrainingBaseViewPage, meta: {title: 'Карточка объекта', permission: ['training_base.view','training_base.edit','training_base.delete']}},
    {path: '/bases/:id/edit', name: 'training-base-edit', component: TrainingBaseEditPage, meta: {title: 'Редактирование объекта', permission: 'training_base.edit'}},

    {path: '/organizations', name: 'organizations-list', component: OrganizationsListPage, meta: {title: 'Организации', role: 'super'}},
    {path: '/organizations/:id', name: 'organizations-view', component: OrganizationsViewPage, meta: {title: 'Карточка организации', role: 'super'}},
    {path: '/organizations/:id/edit', name: 'organizations-edit', component: OrganizationsEditPage, meta: {title: 'Редактирование организации', role: 'super'}},

    {path: '/403', name: '403', component: Forbidden},
    {path: '/:pathMatch(.*)*', name: '404', component: NotFound},
];
