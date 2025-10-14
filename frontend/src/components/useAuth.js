import { ref } from 'vue';
import api from '../Api/AxiosBase';

const user = ref(null);
const loading = ref(true);
const error = ref(null);

export default function useAuth() {
  const fetchUser = async () => {
    try {
      loading.value = true;
      const res = await api.get('profile');
      user.value = res.data.user;
      localStorage.setItem('user', JSON.stringify(user.value)); 
    } catch (err) {
      error.value = err;
      user.value = null;
      localStorage.removeItem('user'); 
      console.error(err);
    } finally {
      loading.value = false;
    }
  };

  return { user, loading, error, fetchUser };
}
