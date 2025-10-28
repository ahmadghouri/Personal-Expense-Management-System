import { defineStore } from "pinia";
import { ref } from "vue";
import api from "../Api/AxiosBase";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";

export const useAuthStore = defineStore("auth", () => {
  const toast = useToast();
  const router = useRouter();

  const user = ref(JSON.parse(localStorage.getItem("user")) || null);
  const role = ref(localStorage.getItem("user_role") || null);
  const loading = ref(false);
  const error = ref(null);

  // const fetchUser = async () => {
  //   try {
  //     loading.value = true;
  //     // const res = await api.get("profile");
  //     // user.value = res.data.user;
  //     // role.value = res.data.user.role;

  //     // localStorage.setItem("user", JSON.stringify(user.value));
  //     // localStorage.setItem("user_role", role.value);
  //     console.log("hello");
      
  //   } catch (err) {
  //     error.value = err;
  //     user.value = null;
  //     role.value = null;
  //     localStorage.removeItem("user");
  //     localStorage.removeItem("user_role");
  //   } finally {
  //     loading.value = false;
  //   }
  // };

  const logout = async () => {
    try {
      await api.post("logout");
    } catch (e) {
      console.error(e);
    } finally {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");
      localStorage.removeItem("user_role");
      toast.success("✅ User logged out");
      router.push({ name: "Login" });
    }
  };

  return { user, role, loading, error, logout };
});
