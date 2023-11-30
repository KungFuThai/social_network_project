<template>
  <div class="d-flex align-center flex-column mt-2 position-relative">

    <VCard
        width="500"
        position="absolute">
      <v-card v-for="(post,index) in posts" :key="index">
        <v-card class="d-flex align-center" :elevation="2">
          <v-avatar class="ml-2">
            <v-img
                width="30"
                height="40"
                cover
                :src="getImageSrc(post['user']['avatar'])"
            >
            </v-img>
          </v-avatar>
          <div>
            <div class="float-left text-h6">{{ getFullName(post['user']['last_name'], post['user']['first_name']) }}
            </div>
            <br>
            <div class="float-left">{{ this.formatDate(post.created_at) }}</div>
          </div>
        </v-card>
        <v-card>
          <v-card-item v-if="post.image !== null">
            <v-card-title>
              {{ post.content }}
            </v-card-title>
            <v-img
                :src="this.getImage(post.image)"
            >
            </v-img>
          </v-card-item>
          <v-card-title v-else>
            {{ post.content }}
          </v-card-title>
        </v-card>
        <v-card class="d-flex align-center" :elevation="2">
          <v-col cols="4">
            <v-btn rounded width="200" :elevation="3"
                   v-bind:color="checkUserReaction(post).type === 1 ? 'lightpink' : ''"
                   @click="reactToPost(post.id, 1)">
              {{ post.likes_count }}
              <v-icon
                  color="gray"
              >
                mdi-thumb-up
              </v-icon>
            </v-btn>
          </v-col>
          <v-col cols="4">
            <v-btn rounded width="200" :elevation="3"
                   v-bind:color="checkUserReaction(post).type === 0 ? 'lightpink' : ''"
                   @click="reactToPost(post.id, 0)">
              {{ post.dislikes_count }}
              <v-icon color="gray">
                mdi-thumb-down
              </v-icon>
            </v-btn>
          </v-col>
          <v-col cols="4">
            <v-dialog
                transition="dialog-bottom-transition"
                scrollable
                width="500"
                height="700"
            >
              <template v-slot:activator="{ props }">
                <v-btn rounded width="200" :elevation="3" v-bind="props" @click="getComments(post.id)">
                  {{ post.comments_count }}
                  <v-icon color="gray">
                    mdi-comment
                  </v-icon>
                </v-btn>
              </template>
              <template v-slot:default="{ isActive }">
                <v-card>
                  <v-toolbar
                      color="lightpink"
                      title="Bình luận"
                  ></v-toolbar>
                  <v-card-text>
                    <v-list lines="one">
                      <v-list-item
                          v-for="comment in comments"
                          :title="getFullName(comment['user']['last_name'], comment['user']['first_name'])"
                          :subtitle="comment.comment"
                          :prepend-avatar="getImageSrc(comment['user']['avatar'])"
                          link
                      >
                        <template v-slot:append
                                  v-if="comment['user']['id'] === currentUser.id || post.user_id === currentUser.id">
                          <v-btn
                              color="red"
                              rounded
                          >
                            <v-icon>
                              mdi-delete
                            </v-icon>
                          </v-btn>
                        </template>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                  <v-card-actions>
                    <v-text-field
                        clearable
                        label="Bình luận"
                        append-icon="mdi-send"
                        variant="solo-filled"
                        placeholder="Viết một bình luận..."
                    >

                    </v-text-field>
                  </v-card-actions>
                  <v-card-actions class="justify-end">
                    <v-btn
                        variant="text"
                        @click="isActive.value = false"
                    >Close
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
          </v-col>
        </v-card>
      </v-card>
    </VCard>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: "PostSection",
  props: {
    currentUser: Object,
    token: String,
  },
  data() {
    return {
      posts: {},
      comments: {},
      userReaction: {},
    }
  },
  created() {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    this.getPosts()
  },
  mounted() {

  },
  methods: {
    async getComments(postId) {
      await axios.get(`api/comments/${postId}`)
          .then((response) => {
            this.comments = response.data.data
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async getPosts() {
      await axios.get(`api/posts`)
          .then((response) => {
            this.posts = response.data.data
          })
          .catch((error) => {
            console.log(error)
          });
    },
    getFullName(lastName, firstName) {
      return lastName + " " + firstName;
    },
    getImageSrc(image) {
      if (image === null || image === '') {
        return 'https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
      }
      return 'http://social_network_project.test/storage/' + image
    },
    getImage(image) {
      return 'http://social_network_project.test/storage/' + image
    },
    formatDate(value) {
      return moment(String(value)).format('hh:mm DD/MM/YYYY')
    },
    checkUserReaction(post) {
      const userReaction = post.reactions.find(reaction => reaction.user_id === this.currentUser.id);
      return userReaction ? userReaction : {type: -1};
    },
    async reactToPost(postId, reactionType) {
      const response = await this.$axios.post(`api/reaction`, {
        post_id: postId,
        type: reactionType
      });

      if (response.data && response.data.data) {
        const postIndex = this.posts.findIndex(post => post.id === postId);

        if (postIndex !== -1) {
          const userReactionIndex = this.posts[postIndex].reactions.findIndex(
              reaction => reaction.user_id === this.currentUser.id
          );

          if (userReactionIndex !== -1) {
            //kiểm tra trả về khi xoá reaction
            if (response.data.data === -1) {
              this.posts[postIndex].reactions.splice(userReactionIndex, 1);
              reactionType === 1 ? this.posts[postIndex].likes_count-- : this.posts[postIndex].dislikes_count--;
            } else {
              if (this.posts[postIndex].reactions[userReactionIndex].type === reactionType) {
                this.posts[postIndex].reactions.splice(userReactionIndex, 1);
                reactionType === 1 ? this.posts[postIndex].likes_count-- : this.posts[postIndex].dislikes_count--;
              } else {
                this.posts[postIndex].reactions[userReactionIndex].type = reactionType;
                if (reactionType === 1) {
                  this.posts[postIndex].likes_count++;
                  this.posts[postIndex].dislikes_count--;
                } else {
                  this.posts[postIndex].dislikes_count++;
                  this.posts[postIndex].likes_count--;
                }
              }
            }
          } else {
            this.posts[postIndex].reactions.push({
              id: response.data.data.id,
              type: response.data.data.type,
              user_id: this.currentUser.id
            });

            reactionType === 1 ? this.posts[postIndex].likes_count++ : this.posts[postIndex].dislikes_count++;
          }
        }
      }
    },
    //next function
  },
}
</script>

<style scoped>

</style>