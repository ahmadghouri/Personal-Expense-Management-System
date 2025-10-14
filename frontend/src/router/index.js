import { createRouter, createWebHistory, useRoute } from "vue-router";
import Login from "../components/Login.vue";
import HomePage from "../Pages/HomePage.vue";
import ForgotPassword from "../components/ForgotPassword.vue";
import ResetPassword from "../components/ResetPassword.vue";
import AdminDashboard from "../Pages/AdminDashboard.vue";
import ManagerDashbord from "../Pages/ManagerDashbord.vue";
import useAuth from "../components/useAuth";

const routes = [
    {
        path: "/",
        name:"Home",
        component:HomePage
    },
    {
        path: "/login",
        name:"Login",
        component:Login
    },
    {
        path:'/forgot-password',
        name:'ForgotPassword',
        component:ForgotPassword
    },
     {
        path: "/reset-password",
        name: "ResetPassword",
        component: ResetPassword
    },
     {
        path: "/admin-dashboard",
        name: "AdminDashbord",
        component: AdminDashboard,
         meta: { requiresAuth: true, role: "admin" }
    },
     {
        path: "/manager-dashboard",
        name: "ManagerDashboard",
        component: ManagerDashbord,
        meta: { requiresAuth: true, role: "manager" }
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
  const user = localStorage.getItem("auth_token");
  const userRole = localStorage.getItem("user_role");



//   if (loading.value) {
//     // Optional: wait or show a loading screen
//     return;
//   }
    // console.log(user.value);

  if (to.meta.requiresAuth && !user) {
    // Not logged in, redirect to login
    next({ name: "Login" });
  } else if (to.meta.role && userRole !== to.meta.role) {
    // Logged in but wrong role, redirect to home
    next({ name: "Home" });
  } else {
    next();
  }
});

export default router;