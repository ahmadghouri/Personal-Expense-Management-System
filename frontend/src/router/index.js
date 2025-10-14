import { createRouter, createWebHistory } from "vue-router";
import Login from "../components/Login.vue";
import HomePage from "../Pages/HomePage.vue";
import ForgotPassword from "../components/ForgotPassword.vue";
import ResetPassword from "../components/ResetPassword.vue";
import AdminDashboard from "../Pages/AdminDashboard.vue";
import ManagerDashboard from "../Pages/ManagerDashbord.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: HomePage,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/forgot-password",
    name: "ForgotPassword",
    component: ForgotPassword,
  },
  {
    path: "/reset-password",
    name: "ResetPassword",
    component: ResetPassword,
  },
  {
    path: "/admin-dashboard",
    name: "AdminDashboard",
    component: AdminDashboard,
    meta: { requiresAuth: true, role: "admin" },
  },
  {
    path: "/manager-dashboard",
    name: "ManagerDashboard",
    component: ManagerDashboard,
    meta: { requiresAuth: true, role: "manager" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");
  const role = localStorage.getItem("user_role");

  if (
    (to.name === "Login" ||
      to.name === "ForgotPassword" ||
      to.name === "ResetPassword") &&
    token
  ) {
    if (role === "admin") {
      return next({ name: "AdminDashboard" });
    } else if (role === "manager") {
      return next({ name: "ManagerDashboard" });
    } else {
      return next({ name: "Home" });
    }
  }
 
  if (to.meta.requiresAuth && !token) {
    return next({ name: "Login" });
  }

  if (to.meta.role && role !== to.meta.role) {
    if (role === "admin") {
      return next({ name: "AdminDashboard" });
    } else {
      return next({ name: "ManagerDashboard" });
    }
  }
  next();
});

export default router;
