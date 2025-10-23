import { createRouter, createWebHistory } from "vue-router";

// 🔹 Auth Pages
import Login from "../Pages/auth/Login.vue";
import ForgotPassword from "../Pages/auth/ForgotPassword.vue";
import ResetPassword from "../Pages/auth/ResetPassword.vue";

// 🔹 Layouts
import AdminLayout from "../layouts/AdminLayout.vue";
import ManagerLayout from "../layouts/ManagerLayout.vue";

// 🔹 Admin Pages
import AdminDashboard from "../Pages/admin/Dashboard.vue";
import AdminExpenses from "../Pages/admin/Expenses.vue";
import AdminDonation from "../Pages/admin/Donation.vue";
import AdminCategory from "../Pages/admin/Category.vue";
import AdminDonationTypes from "../Pages/admin/DonationTypes.vue";
import AdminReports from "../Pages/admin/Reports.vue"
import AdminUser from "../Pages/admin/User.vue"
// 🔹 Manager Pages
import ManagerDashboard from "../Pages/manager/Dashboard.vue";
import ManagerReports from "../Pages/manager/Reports.vue";
import ManagerExpenses from "../Pages/manager/Expenses.vue";
import ManagerDonation from "../Pages/manager/Donation.vue";
// import ManagerReports from "../Pages/manager/Reports.vue";

const routes = [
  // Public Routes
  { path: "/", name: "Login", component: Login },
  { path: "/forgot-password", name: "ForgotPassword", component: ForgotPassword },
  { path: "/reset-password", name: "ResetPassword", component: ResetPassword },

  // 🔹 Admin Routes
  {
    path: "/admin",
    component: AdminLayout,
    meta: { requiresAuth: true, role: "admin" },
    children: [
      { path: "dashboard", name: "AdminDashboard", component: AdminDashboard },
      { path:'expenses', name:'AdminExpenses', component:  AdminExpenses},
      { path:'donation', name:'AdminDonation', component:  AdminDonation},
      { path:'category', name:'AdminCategory', component:  AdminCategory},
      { path:'donation-types', name:'AdminDonationTypes', component:  AdminDonationTypes},
      { path:'reports', name:'AdminReports', component:  AdminReports},
      { path:'user', name:'AdminUser', component:  AdminUser},
      // future admin routes
    ],
  },

  // 🔹 Manager Routes
  {
    path: "/manager",
    component: ManagerLayout,
    meta: { requiresAuth: true, role: "manager" },
    children: [
      { path: "dashboard", name: "ManagerDashboard", component: ManagerDashboard },
      { path: "expenses", name: "ManagerExpenses", component: ManagerExpenses },
      { path: "donation", name: "ManagerDonation", component: ManagerDonation },
      { path: "reports", name: "ManagerReports", component: ManagerReports  },
    ],
  },

  // 🔹 Redirects for backward compatibility
  { path: "/admin-dashboard", redirect: "/admin/dashboard" },
  { path: "/manager-dashboard", redirect: "/manager/dashboard" },

  {
  path: "/:pathMatch(.*)*",
  name: "NotFound",
  component: () => import("../Pages/NotFound.vue"),
}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// 🔹 Auth Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");
  const role = localStorage.getItem("user_role");

  // If already logged in and visiting auth pages
  if (["Login", "ForgotPassword", "ResetPassword"].includes(to.name) && token) {
    if (role === "admin") return next({ name: "AdminDashboard" });
    if (role === "manager") return next({ name: "ManagerDashboard" });
  }

  // Require login
  if (to.meta.requiresAuth && !token) return next({ name: "Login" });

  // Role-based access
  if (to.meta.role && role !== to.meta.role) {
    return role === "admin"
      ? next({ name: "AdminDashboard" })
      : next({ name: "ManagerDashboard" });
  }

  next();
});

export default router;
