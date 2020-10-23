import DashboardLayout from "../pages/Layout/DashboardLayout.vue";
// Dashboard Component
import Dashboard from "../pages/Dashboard.vue";
// Users Components
import Users from "../pages/Users/List.vue";
import UsersCreate from "../pages/Users/Create.vue";
import UsersShow from "../pages/Users/Show.vue";
import UsersEdit from "../pages/Users/Edit.vue";
import UsersDelete from "../pages/Users/Delete.vue";
// Role Tenant Registration
import TenantRegistration from "../pages/RegistrationRole/TenantRegistration.vue";
import OwnerRegistration from "../pages/RegistrationRole/OwnerRegistration.vue";
import AgentRegistration from "../pages/RegistrationRole/AgentRegistration.vue";
import ServiceRegistration from "../pages/RegistrationRole/ServiceRegistration.vue";
// Properties Components
import Properties from "../pages/Properties/List.vue";
import PropertiesExecutive from "../pages/Properties/ListExecutive.vue";
import PropertiesCreate from "../pages/Properties/Create.vue";
import PropertiesEdit from "../pages/Properties/Edit.vue";
import PropertiesDelete from "../pages/Properties/Delete.vue";
// Projects Components
import Projects from "../pages/Projects/List.vue";
import ProjectsEdit from "../pages/Properties/Edit.vue";
import ProjectsDelete from "../pages/Projects/Delete.vue";
// Memberships Components
import Memberships from "../pages/Memberships/List.vue";
import MembershipsEdit from "../pages/Memberships/Edit.vue";
import UsersHasMembership from "../pages/Memberships/UsersHasMembership.vue";
import UserHasMembershipEdit from "../pages/Memberships/UserHasMembershipEdit.vue";
import UserHasMembershipDelete from "../pages/Memberships/UserHasMembershipDelete.vue";
import UsersMultirole from "../pages/Memberships/UsersMultirole.vue";
// Contracts Components
import Contracts from "../pages/Contracts/List.vue";
// Services
import Services from "../pages/Services/List.vue";
// Settings Components
import Settings from "../pages/Settings/Main.vue";
// Income Components
import Income from "../pages/Income/Main.vue";
// Template Default Components
import UserProfile from "../pages/UserProfile.vue";
import Notifications from "../pages/Notifications.vue";
// Payments Components
import Payments from "../pages/Payments/List.vue";
// Scoring Components
import Scoring from "../pages/Scoring/List.vue";
import ScoringCreate from "../pages/Scoring/Create.vue";
import ScoringEdit from "../pages/Scoring/Edit.vue";
import ScoringCategories from "../pages/Scoring/ScoringCategories.vue";
import ScoringCategoryDetails from "../pages/Scoring/ScoringCategoryDetails.vue";
import AddScoringCategory from "../pages/Scoring/AddScoringCategory.vue";
import EditScoringCategory from "../pages/Scoring/EditScoringCategory.vue";
import AddScoringCategoryDetail from "../pages/Scoring/AddScoringCategoryDetail.vue";
import EditScoringCategoryDetail from "../pages/Scoring/EditScoringCategoryDetail.vue";

// Users admin Components
import UsersAdmin from "../pages/UsersAdmin/List.vue";
import UsersAdminCreate from "../pages/UsersAdmin/Create.vue";
import UsersAdminEdit from "../pages/UsersAdmin/Edit.vue";
import UsersAdminDelete from "../pages/UsersAdmin/Delete.vue";

//User Admin Components

import UserAdmin from "../pages/UserAdmin/List.vue";
import UserAdminCreate from "../pages/UserAdmin/Create.vue";
import UserAdminEdit from "../pages/UserAdmin/Edit.vue";
import UserAdminDelete from "../pages/UserAdmin/Delete.vue";




// Coupons Components
import Coupons from "../pages/Coupons/List.vue";
import CouponsCreate from "../pages/Coupons/Create.vue";
import CouponsEdit from "../pages/Coupons/Edit.vue";
import CouponsShow from "../pages/Coupons/Show.vue";



// Configurations Components
import Configurations from "../pages/Configurations/Show.vue";

const routes = [
  {
    path: "/",
    component: DashboardLayout,
    redirect: "/dashboard",
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        component: Dashboard
      }
    ]
  },
  // Users
  {
    path: "/users",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Usuarios",
        component: Users
      },
      {
        path: ":userId/show",
        name: "Datos del Usuario",
        component: UsersShow
      },
      {
        path: "create",
        name: "Crear Usuario",
        component: UsersCreate
      },
      {
        path: ":userId/edit",
        name: "Editar Datos del Usuario",
        component: UsersEdit
      },
      {
        path: ":userId/delete",
        name: "Eliminar Usuario",
        component: UsersDelete
      }
    ]
  },
  // Users Admin
  {
    path: "/users-admin",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "UsuariosAdmin",
        component: UsersAdmin
      },
      {
        path: "create",
        name: "Crear Usuario Backend",
        component: UsersAdminCreate
      },
      {
        path: ":userId/edit",
        name: "Editar Datos del Usuario Backend",
        component: UsersAdminEdit
      },
      {
        path: ":userId/delete",
        name: "Eliminar Usuario Backend",
        component: UsersAdminDelete
      }
    ]
  },

  // User admin


  {
    path: "/user-admin",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "UserAdmin",
        component: UserAdmin
      },
      {
        path: "create",
        name: "userAdmin Create",
        component: UserAdminCreate
      },
      {
        path: ":userId/edit",
        name: "userAdmin Edit",
        component: UserAdminEdit
      },
      {
        path: ":userId/delete",
        name: "userAdmin Delete",
        component: UserAdminDelete
      }
    ]
  },


  // Coupons Routes
  {
    path: "/coupons",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Cupones",
        component: Coupons
      },
      {
        path: "create",
        name: "Crear Cupon",
        component: CouponsCreate
      },
      {
        path: "edit/:couponId",
        name: "Editar Cupon",
        component: CouponsEdit
      },
      {
        path: "show/:couponId",
        name: "Ver Cupon",
        component: CouponsShow
      }
    ]
  },

  {
    path: "/configurations",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path:"/",
        name:"Configuraciones",
        component: Configurations
      }
    ]
  },

  // Roles Registration
  {
    path: "/users/:userId/roles",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "tenant",
        name: "Registro de Arrendatario",
        component: TenantRegistration
      },
      {
        path: "owner",
        name: "Registro de Arrendador",
        component: OwnerRegistration
      },
      {
        path: "agent",
        name: "Registro de Agente",
        component: AgentRegistration
      },
      {
        path: "service",
        name: "Registro de Servicio",
        component: ServiceRegistration
      }
    ]
  },
  // Properties
  {
    path: "/properties",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Propiedades",
        component: Properties
      },
      {
        path: "executive",
        name: "PropiedadesEjecutivas",
        component: PropertiesExecutive
      },
      {
        path: ":propertyId/edit",
        name: "Editar Datos de la Propiedad",
        component: PropertiesEdit
      },
      {
        path: "create",
        name: "Crear una Nueva Propiedad",
        component: PropertiesCreate
      },
      {
        path: ":propertyId/delete",
        name: "Eliminar Propiedad",
        component: PropertiesDelete
      }
    ]
  },
  // Projects
  {
    path: "/projects",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Proyectos",
        component: Projects
      },
      {
        path: ":projectId/edit",
        name: "Editar Datos del Proyecto",
        component: ProjectsEdit,
        props: { isProject: true }
      },
      {
        path: ":projectId/delete",
        name: "Eliminar Proyecto",
        component: ProjectsDelete
      }
    ]
  },
  // Services
  {
    path: "/services",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Services",
        component: Services
      },
    ]
  },
  // Payments
  {
    path: "/payments",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Pagos",
        component: Payments
      }
    ]
  },
  // Scoring
  {
    path: "/scoring",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Scoring",
        component: Scoring
      },
      {
        path: "create",
        name: "Nuevo Scoring",
        component: ScoringCreate
      },
      {
        path: ":scoringId/edit",
        name: "Editar Datos del Scoring",
        component: ScoringEdit
      },
      {
        path: ":scoringId/categories",
        name: "Categorias",
        component: ScoringCategories
      },
      {
        path: ":scoringId/categories/:scoringCategoryId/edit",
        name: "Editar Categoria",
        component: EditScoringCategory
      },
      {
        path: ":scoringId/categories/add",
        name: "Agregar Categoria",
        component: AddScoringCategory
      },
      {
        path: ":scoringId/categories/:scoringCategoryId/details",
        name: "Detalles de la Categoria",
        component: ScoringCategoryDetails
      },
      {
        path: ":scoringId/categories/:scoringCategoryId/details/add",
        name: "Agregar Detalle de Categoria",
        component: AddScoringCategoryDetail
      },
      {
        path: ":scoringId/categories/:scoringCategoryId/details/:detailId/edit",
        name: "Editar Detalle de Categoria",
        component: EditScoringCategoryDetail
      },
    ]
  },
  // Memberships
  {
    path: "/memberships",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Membresias",
        component: Memberships
      },
      {
        path: ":membershipId/edit",
        name: "Editar Datos de Membresias",
        component: MembershipsEdit
      },
      {
        path: ":membershipId/users",
        name: "Usuarios de la Membresia",
        component: UsersHasMembership
      },
      {
        path: ":membershipId/users/:userId",
        name: "Informacion de Usuario",
        component: UserHasMembershipEdit
      },
      {
        path: ":membershipId/users/:userId/delete",
        name: "Eliminar Usuario de la Membresia",
        component: UserHasMembershipDelete
      },
      {
        path: "users/multirole",
        name: "Usuarios con Multiples Perfiles",
        component: UsersMultirole
      }
    ]
  },
  // Contracts
  {
    path: "/contracts",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Contratos",
        component: Contracts
      }
    ]
  },
  // Income
  {
    path: "/income",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Ingresos a la Plataforma",
        component: Income
      }
    ]
  },
  // Settings
  {
    path: "/settings",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Configuracion del Sistema",
        component: Settings
      }
    ]
  },
  // Profile
  {
    path: "/profile",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Perfil de Usuario",
        component: UserProfile
      }
    ]
  },
  // Notifications
  {
    path: "/notifications",
    component: DashboardLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Notificaciones",
        component: Notifications
      }
    ]
  },
  // 404
  { path: "*", redirect: "/dashboard" }
];
export default routes;
