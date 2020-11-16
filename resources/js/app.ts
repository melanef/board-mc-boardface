import Vue from "vue";
import VueMaterial from "vue-material";
import "vue-material/dist/vue-material.min.css";
import "vue-material/dist/theme/default.css";
import Board from "./components/Board";
import MessageService from "./Services/MessageService";
import UserService from "./Services/UserService";

Vue.use(VueMaterial);

const userService = new UserService();
const messageService = new MessageService(userService);

const app = new Vue({
  name: 'Root',
  components: {
    Board,
  },
  provide: {
    userService: userService,
    messageService: messageService,
  },
});

app.$mount('#app');
