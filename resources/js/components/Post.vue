<template>
  <div class="post p-3 row no-gutters" :id="'p' + post.id">
    <div class="col-auto mr-3">
      <a :href="post.user.link">
        <img :src="post.user.avatar_link" class="post-image rounded">
      </a>
    </div>
    <div class="col">
      <template v-if="!post.discussion.private">
        <div class="float-right">
          <a
            class="mr-1"
            href="javascript:void(0)"
            v-clipboard:copy="post.link"
            v-clipboard:success="onCopy"
            v-clipboard:error="onError"
          >
            <i class="fas fa-fw fa-link"></i>
          </a>
          <template v-if="auth_user">
            <!-- <a class="mr-1" href="javascript:void(0)" data-placement="left" data-popover-content="#react_to_post.id" data-toggle="popover" data-trigger="focus"><i class="far fa-fw fa-smile"></i></a> -->
            <template v-if="!post.deleted_at">
              <a class="mr-1" href="#reply" data-action="quote-post" :data-id="post.id">
                <i class="fas fa-fw fa-quote-right"></i>
              </a>
            </template>
            <template
              v-if="(auth_user && post.user.id == auth_user.id && !post.deleted_at) || auth_user_can('bypass discussions guard')"
            >
              <a
                class="mr-1"
                :href="route('discussions.posts.edit', [post.discussion.id, post.discussion.slug, post.id])"
              >
                <i class="fas fa-fw fa-edit"></i>
              </a>
            </template>
            <template
              v-if="(auth_user && post.user.id == auth_user.id && !post.deleted_at) || auth_user_can('bypass discussions guard') && !post.deleted_at"
            >
              <a
                class="mr-1 text-danger"
                :href="route('discussions.posts.delete', [post.discussion.id, post.discussion.slug, post.id])"
              >
                <i class="fas fa-fw fa-trash"></i>
              </a>
            </template>
          </template>
        </div>
      </template>

      <a :href="post.user.link">
        <strong>{{ post.user.display_name }}</strong>
      </a>
      <small>@{{ post.user.name }}</small>
      <br>

      <small>
        <a :href="post.link">{{ post.presented_date }}</a>
      </small>

      <hr>

      <div class="post-content">
        <div v-if="post.deleted_at" class="text-danger mb-3">
          <i class="fas fa-times"></i> Message supprimé
        </div>
        <div v-if="!post.deleted_at || auth_user_can('read deleted posts')">
          <div
            :class="{'deleted-message-content text-italic text-muted': (post.deleted_at)}"
            v-html="post.presented_body"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import iziToast from "izitoast";

export default {
  props: ["post"],
  methods: {
    onCopy: function(e) {
      iziToast.success({
        title: "Lien copié !",
        message: "Le lien vers le message a été copié dans le presse-papiers"
      });
    },
    onError: function(e) {
      iziToast.error({
        title: "Arf, ça marche pas !",
        message: "Impossible de copier le lien (psst, change de navigateur)"
      });
    }
  }
};
</script>