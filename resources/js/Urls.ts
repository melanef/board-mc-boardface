export default {
  USERS: {
    READ: `${process.env.APP_URL}/api/user`,
    LOGIN: `${process.env.APP_URL}/auth/login`,
    LOGOUT: `${process.env.APP_URL}/auth/logout`,
  },
  MESSAGES: {
    CREATE: `${process.env.APP_URL}/api/messages`,
    READ: `${process.env.APP_URL}/api/messages/`,
    UPDATE: `${process.env.APP_URL}/api/messages/`,
    DELETE: `${process.env.APP_URL}/api/messages/`,
    LIST: `${process.env.APP_URL}/api/messages`,
  }
};
