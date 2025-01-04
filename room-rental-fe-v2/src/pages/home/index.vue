<script setup lang="ts">
</script>

<template>
  <VRow class="gap-y-4">
    <VCol :cols="4">
      <img src="@/assets/images/carousel-dang-nhanh-thue-le.jpg" alt="">
    </VCol>
    <VCol :cols="4">
      <img src="@/assets/images/khuyen-mai-50-cho-thanh-vien-moi.jpg" alt="">
    </VCol>
    <VCol :cols="4">
      <img src="@/assets/images/carousel-phongtro-sinh-vien.jpg" alt="">
    </VCol>
  </VRow>

  <p class="text-h5 pt-4">Tin đăng mới cập nhật</p>
  <VRow>
    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2024/11/29/img-4082_1732875421.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2024/11/29/img-4082_1732875421.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2024/11/29/img-4082_1732875421.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2024/11/29/img-4082_1732875421.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

  <p class="text-h5 pt-4">Cho Thuê phòng trọ</p>
  <VRow>
    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2024/11/29/img-4082_1732875421.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2024/11/29/img-4082_1732875421.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2019/02/25/63b548ae81a263fc3ab3_1551067966.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="3">
      <VCard>
        <VCardText>
          <img src="https://pt123.cdn.static123.com/images/thumbs/450x300/fit/2019/02/25/63b548ae81a263fc3ab3_1551067966.jpg">
          <p class="line-clamp-2">Cho thuê biệt thự Phố Đông Village, Cát Lái, Q2</p>
          <p><span style="color: #068906;">9 triệu/tháng</span> 75 m2</p>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
img {
  width: 100%;
  border-radius: 5px;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
  white-space: normal;
  color: #055699;
}
</style>

<route lang="yaml">
meta:
  layout: user
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
