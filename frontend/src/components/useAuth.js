import { ref, onMounted } from 'vue';
import api from '../Api/AxiosBase';

export default function useAuth() {
    const user = ref(null);
    const loading = ref(true);
    const error = ref(null);

    const fetchUser = async () => {
        try {
            const res = await api.get('profile');
            console.log(res)
            user.value = res.data.user;
        } catch (err) {
            error.value = err;
            console.error(err);
        } finally {
            loading.value = false;
        }
    };

    return { user, loading, error, fetchUser };
}