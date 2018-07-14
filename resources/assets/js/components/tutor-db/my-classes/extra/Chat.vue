 <template>
    <div class="chat">
        <main>
          <section>
            <MessageList :messages="messages" />
            <MessageInput :onSend="onSend" />
          </section>
        </main>
    </div>
  </template>

<script>
import MessageList from './MessageList';
import MessageInput from './MessageInput';

export default {
  name: 'Chat',
  updated() {
    document.querySelectorAll('.messages')[0].scrollTop = 10000000; // eslint-disable-line
  },
  props: [
    'onSend',
    'messages',
  ],
  components: {
    MessageList,
    MessageInput,
  },
};
</script>

<style scoped>
  .chat {
    height: 100%;
  }
  header {
    position: fixed;
    height: 40px;
    padding: 10px;
    background: white;
    width: 100%;
    border-bottom: solid 1px #ddd;
    display: flex;
    flex-direction: row;
  }
  header h1 {
    font-size: 20px;
    margin-right: 10px;
  }
  main {
    padding-top: 40px;
    height: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: row;
  }
  section {
    flex: 1;
    display: flex;
    height: 100%;
    flex-direction: column;
  }
  aside {
    width: 200px;
  }
</style>