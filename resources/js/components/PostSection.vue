<template>
  <div class="d-flex align-center flex-column mt-2 position-relative">
    <VCard
        width="500"
        position="absolute">
      <v-card v-for="(post,index) in posts" :key="index">
        <v-card class="d-flex align-center justify-space-between" :elevation="2">
          <div class="d-flex justify-space-between">
            <div class="row" style="display: inline-flex">
              <div class="col-2">
                <v-avatar class="mt-2 mr-1 ml-2">
                  <v-img
                      width="30"
                      height="40"
                      cover
                      :src="getImageSrc(post['user']['avatar'])"
                  >
                  </v-img>
                </v-avatar>
              </div>
              <div class="col-8 float-left">
                <!-- Tên và thời gian -->
                <div class="text-h6" link>
                  <router-link :to="`/profile/${post.user.id}`" class="text-decoration-none text-black">
                    {{ getFullName(post['user']['last_name'], post['user']['first_name']) }}
                  </router-link>
                </div>
                <div>{{ this.formatDate(post.created_at) }}</div>
              </div>
            </div>
          </div>
          <v-spacer></v-spacer>
          <div class="col-2 mr-2" v-if="post.user_id == currentUser.id">
            <v-menu>
              <template v-slot:activator="{ props }">
                <v-btn icon="mdi-dots-vertical" v-bind="props"></v-btn>
              </template>
              <v-list>
                <v-list-item>
                  <v-dialog
                      transition="dialog-top-transition"
                      scrollable
                      width="600"
                      height="500"
                  >
                    <template v-slot:activator="{ props }">
                      <v-btn
                          @click="getPostForEdit(index)"
                          rounded
                          color="blue"
                          icon="mdi-pen"
                          v-bind="props"
                      ></v-btn>
                    </template>
                    <template v-slot:default="{ isActive }">
                      <v-card>
                        <v-toolbar
                            color="lightpink"
                            title="Sửa bài đăng"
                        ></v-toolbar>
                        <v-card-text>
                          <v-form enctype="multipart/form-data">
                            <v-container>
                              <v-row>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                  <v-textarea
                                      label="Nội dung bài đăng"
                                      hide-details="auto"
                                      required
                                      v-model="formData.content"
                                  ></v-textarea>
                                </v-col>

                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                  <v-row>
                                    <v-file-input label="Ảnh của bài đăng" @change="onFileImageChange"
                                                  :error-messages="errors.image"></v-file-input>
                                  </v-row>
                                  <v-row class="d-flex justify-center" v-if="postImage || formData.image">
                                    <img class="img-responsive" :src="post.image ? getImage(postImage) : postImage"
                                         height="200" width="200">
                                  </v-row>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="12"
                                >
                                  <v-radio-group inline label="Trạng thái bài đăng"
                                                 v-model="formData.status"
                                                 :error-messages="errors.status">
                                    <v-radio label="Ẩn" :value="0"></v-radio>
                                    <v-radio label="Hiện" :value="1"></v-radio>
                                  </v-radio-group>
                                </v-col>
                                <v-col>
                                  <div class="d-flex justify-center">
                                    <v-btn color="blue"
                                           prepend-icon="mdi-plus" @click="updatePost(post.id)"
                                           @keyup.enter="updatePost(post.id)">
                                      Sửa bài đăng
                                    </v-btn>
                                  </div>
                                </v-col>

                              </v-row>
                            </v-container>
                          </v-form>
                        </v-card-text>
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
                </v-list-item>
                <v-list-item>
                  <v-btn rounded color="gray"
                         :icon="post.status == 1 ? 'mdi-eye' : 'mdi-eye-off'"
                         @click="post.status = !post.status; updatePostStatus(post.id, post.status)"
                  ></v-btn>
                </v-list-item>
                <v-list-item>
                  <v-btn rounded color="red" icon="mdi-delete" @click="deletePost(post.id,index)"></v-btn>
                </v-list-item>
              </v-list>
            </v-menu>
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
                id="comment-dialog"
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
                          class="mb-2"
                          rounded="lg"
                          :elevation="1"
                          v-for="(comment,index) in comments"
                          :title="getFullName(comment.user.last_name, comment.user.first_name)"
                          :subtitle="formatDate(comment.created_at)"
                          :prepend-avatar="getImageSrc(comment.user.avatar)"
                          link
                      >
                        <span>
                          {{ comment.comment }}
                        </span>
                        <template v-slot:append>
                          <v-btn
                              @click="getCommentForEdit(comment,index)"
                              v-if="comment['user']['id'] === currentUser.id"
                              color="cyan"
                              rounded
                          >
                            <v-icon>
                              mdi-pen
                            </v-icon>
                          </v-btn>
                          <v-btn
                              @click="deleteComment(comment,index,post)"
                              v-if="comment['user']['id'] === currentUser.id || post.user_id === currentUser.id"
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
                        v-if="isEditComment"
                        clearable
                        label="Sửa bình luận"
                        append-icon="mdi-send"
                        variant="solo-filled"
                        placeholder="Viết một bình luận..."
                        v-model="editComment"
                        prepend-icon="mdi-close"
                        @click:prepend="isEditComment = false; isComment=true"
                        @click:append="updateComment(commentId)"
                        @keyup.enter="updateComment(commentId)"
                    >
                    </v-text-field>
                  </v-card-actions>
                  <v-card-actions>
                    <v-text-field
                        v-bind:disabled="isComment != true"
                        clearable
                        label="Bình luận"
                        append-icon="mdi-send"
                        variant="solo-filled"
                        placeholder="Viết một bình luận..."
                        v-model="comment"
                        @click:append="addComment(post.id)"
                        @keyup.enter="addComment(post.id)"
                    >
                    </v-text-field>
                  </v-card-actions>
                  <v-card-actions class="justify-end">
                    <v-btn
                        variant="text"
                        @click="isActive.value = false"
                    >
                      Đóng
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
  <v-snackbar
      v-if="message !== ''"
      v-model="snackbar"
      :timeout=2000
      color="green"
  >
    {{ message }}
  </v-snackbar>
</template>

<script>
import moment from 'moment';

export default {
  name: "PostSection",
  props: {
    currentUser: Object,
    token: String,
    profilePosts: Object,
  },
  data() {
    return {
      snackbar: false,
      message: '',
      posts: {},
      comment: '',
      commentId: '',
      editComment: '',
      isComment: true,
      isEditComment: false,
      editCommentIndex: null,
      comments: [],
      userReaction: {},
      formData: {
        content: '',
        image: null,
        status: '',
      },
      errors: {
        content: '',
        image: '',
        status: '',
      },
      postIndex: '',
      postImage: '',
    }
  },
  created() {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
  },
  mounted() {
    if (this.profilePosts === null || this.profilePosts === undefined) {
      this.getPosts()
    } else {
      setTimeout(() => {
        this.posts = this.profilePosts
      }, 1000);
    }
  },
  methods: {
    async getComments(postId) {
      await axios.get(`/api/comments/${postId}`)
          .then((response) => {
            this.comments = response.data.data
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async getPosts() {
      await axios.get(`/api/posts`)
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
      return '/storage/' + image
    },
    getImage(image) {
      return '/storage/' + image
    },
    formatDate(value) {
      return moment(String(value)).format('hh:mm DD/MM/YYYY')
    },
    checkUserReaction(post) {
      const userReaction = post.reactions.find(reaction => reaction.user_id === this.currentUser.id);
      return userReaction ? userReaction : {type: -1};
    },
    async reactToPost(postId, reactionType) {
      const response = await this.$axios.post(`/api/reaction`, {
        post_id: postId,
        type: reactionType
      });

      if (response.data && response.data.data) {
        //tìm index theo mã bài đăng
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
            //loại reaction là gì thì tăng số lượng cái đó
            reactionType === 1 ? this.posts[postIndex].likes_count++ : this.posts[postIndex].dislikes_count++;
          }
        }
      }
    },
    async addComment(postId) {
      if (this.comment === '') return;
      await axios.post(`/api/comments/${postId}/add`, {
        comment: this.comment
      })
          .then((response) => {
            const postIndex = this.posts.findIndex(post => post.id === postId);
            Array.prototype.push.apply(this.comments, response.data.data);
            this.comment = '';
            if (postIndex !== -1) {
              this.posts[postIndex].comments_count++;
            }
            this.message = response.data.message
            this.snackbar = true
          });
    },
    async getCommentForEdit(comment, index) {
      if (comment.user_id != this.currentUser.id) return;
      await axios.get(`/api/comments/${comment.id}/edit`)
          .then((response) => {
            this.editComment = response.data.data.comment;
            this.isComment = false
            this.isEditComment = true
            this.editCommentIndex = index
            this.commentId = comment.id
          });
    },
    async updateComment(commentId) {
      if (this.editComment === '') return;
      await axios.post(`/api/comments/${commentId}/edit`, {
        comment: this.editComment
      })
          .then((response) => {
            let responseData = response.data.data
            // vì this.comments.splice(index, 1, response.data.data); thêm một arr con trong arr nên phải dùng cách này
            for (let i = 0; i < responseData.length; i++) {
              this.comments.splice(this.editCommentIndex + i, 1, responseData[i]);
            }
            this.editComment = '';
            this.isComment = true
            this.isEditComment = false

            this.message = response.data.message
            this.snackbar = true
          });
    },
    async deleteComment(comment, index, post) {
      if (comment.user_id == this.currentUser.id || post.user_id == this.currentUser.id) {
        await axios.delete(`/api/comments/${comment.id}`)
            .then((response) => {
              let postId = post.id;
              this.comments.splice(index, 1)
              const postIndex = this.posts.findIndex(post => post.id === postId);
              if (postIndex !== -1) {
                this.posts[postIndex].comments_count--;
              }
              this.message = response.data.message
              this.snackbar = true
            });
      }
    },
    async deletePost(postId, index) {
      await axios.delete(`/api/posts/${postId}`)
          .then((response) => {
            this.posts.splice(index, 1)
            this.message = response.data.message
            this.snackbar = true
          })
    },
    async updatePostStatus(postId, status) {
      await axios.post(`/api/posts/update-status/${postId}`, {
        status: status
      })
          .then((response) => {
            console.log(1)
            this.message = response.data.message
            this.snackbar = true
          })
    },
    onFileImageChange(e) {
      const file = e.target.files[0];
      this.formData.image = file
      this.postImage = URL.createObjectURL(file);
    },
    getPostForEdit(index) {
      let post = this.posts[index];
      this.postImage = post.image
      this.formData.status = post.status
      this.formData.content = post.content
      this.postIndex = index
    },
    updatePost(postId) {
      const formData = new FormData();
      if (this.formData.image != null) {
        formData.append('image', this.formData.image, this.formData.image.name);
      }
      formData.append('status', this.formData.status);
      formData.append('content', this.formData.content);
      axios.post(`/api/posts/${postId}/edit`, formData)
          .then((response) => {
            let responseData = response.data.data;
            this.message = response.data.message;
            this.snackbar = true;
            if (responseData.status == 1) {
              for (let i = 0; i < responseData.length; i++) {
                this.posts.splice(this.postIndex + i, 1, responseData[i]);
              }
            } else {
              this.posts[this.postIndex].id = responseData.id;
              this.posts[this.postIndex].content = responseData.content;
              this.posts[this.postIndex].status = responseData.status;
              this.posts[this.postIndex].user_id = responseData.user_id;
              this.posts[this.postIndex].created_at = responseData.created_at;
              this.posts[this.postIndex].updated_at = responseData.updated_at;
            }
          })
          .catch((errors) => {
            this.errors = errors.response.data.errors
          })
    },
    //next function
  },
}
</script>

<style scoped>

</style>