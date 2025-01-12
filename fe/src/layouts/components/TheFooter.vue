<template functional>
  <footer class="the-footer" :class="classes">
    <p>
      Để được duyệt bài viết, vui lòng liên hệ với Admin qua email:
      <a href="mailto:admin@example.com">admin@example.com</a>
    </p>
  </footer>
</template>

<script>
export default {
  name: "the-footer",
  props: {
    classes: {
      type: String,
    },
  },
};
</script>
