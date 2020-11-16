<template>
  <md-app md-mode="reveal">
    <md-app-toolbar>
      <h3 class="md-title md-layout-item">Message Board</h3>

      <div class="md-layout-item md-size-60" v-if="isLogged">
        <md-button @click="onNewMessage">New Message</md-button>
      </div>

      <div class="md-layout-item md-size-20" v-if="isLogged">
        <md-button @click="onLogout">Logout</md-button>
      </div>
    </md-app-toolbar>

    <md-app-content>
      <md-progress-spinner md-mode="indeterminate" v-if="loading"></md-progress-spinner>
      <div v-if="isLogged">
        <message-list
          v-if="!loading"
          ref="list"
          @message:view="onMessageView"
          @message:edit="onMessageEdit"
          @message:delete="onMessageDelete"
        ></message-list>

        <message-form
          v-if="showForm"
          :message-id="message.id"
          @submit="onMessageSubmit"
          @close="showForm = false"
        ></message-form>

        <message-view
          v-if="showCard && message"
          :message="message"
          @message:edit="onMessageEdit"
          @message:delete="onMessageDelete"
          @close="showCard = false"
        ></message-view>
      </div>
      <login v-if="!loading && !isLogged" @logged="onLogin"></login>
    </md-app-content>
  </md-app>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import { Inject } from "vue-property-decorator";
import Message from "../Models/Message";
import MessageService from "../Services/MessageService";
import MessageList from "./Messages/MessageList";
import MessageForm from "./Messages/MessageForm";
import MessageView from "./Messages/MessageView.vue";
import UserService from "../Services/UserService";
import User from "../Models/User";
import Login from "./Login";

@Component({
  components: {
    Login,
    MessageForm,
    MessageList,
    MessageView,
  }
})
export default class Board extends Vue {
  $refs!: {
    list: MessageList
  };

  @Inject() readonly userService!: UserService;
  @Inject() readonly messageService!: MessageService;

  loading: boolean = false;

  message: Message | null = null;

  showForm: boolean = false;
  showCard: boolean = false;

  user: User | null = null;
  isLogged: boolean = false;

  created() {
    this.loading = true;
    this.userService.getCurrentUser()
      .then(user => {
        this.user = user;
        this.isLogged = true;
        this.loading = false;
      })
      .catch(() => {
        this.isLogged = false;
        this.loading = false;
      });
  }

  onLogin(user: User) {
    this.user = user;
    this.isLogged = true;
    this.loading = false;
    this.$nextTick(() => {
      this.$forceUpdate();
    });
  }

  onLogout() {
    this.userService.logout();
    //this.isLogged = false;
  }

  onMessageView(message: Message) {
    this.message = message;
    this.showForm = false;
    this.showCard = true;
  }

  onNewMessage() {
    this.message = null;
    this.showCard = false;
    this.showForm = true;
  }

  onMessageEdit(message: Message) {
    this.message = message;
    this.showCard = false;
    this.showForm = true;
  }

  onMessageSubmit() {
    this.$refs.list.load();
  }

  onMessageDelete(message: Message) {
    this.loading = true;
    this.messageService.delete(message)
        .then(() => {
          this.showCard = false;
          this.$refs.list.load();
        });
  }
}
</script>
