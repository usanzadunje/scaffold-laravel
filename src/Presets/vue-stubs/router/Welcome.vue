<template>
  <div>
    Welcome!!!
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { useRouter }       from 'vue-router';

export default defineComponent({
  name: 'Welcome',
  setup() {
    const router = useRouter();

    return {
      router,
    };
  },
});
</script>