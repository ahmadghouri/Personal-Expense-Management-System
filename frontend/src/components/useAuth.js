import { ref } from "vue";
import api from "../Api/AxiosBase";

const user = ref(null);
const role = ref(localStorage.getItem("user_role") || null);
const loading = ref(true);
const error = ref(null);

export default function useAuth() {
  const fetchUser = async () => {
    try {
      loading.value = true;
      const res = await api.get("profile");
      user.value = res.data.user;
      role.value = res.data.user.role;

      localStorage.setItem("user", JSON.stringify(user.value));
      localStorage.setItem("user_role", role.value);
    } catch (err) {
      error.value = err;
      user.value = null;
      role.value = null;
      localStorage.removeItem("user");
      localStorage.removeItem("user_role");
    } finally {
      loading.value = false;
    }
  };

  return { user, role, loading, error, fetchUser };
}
