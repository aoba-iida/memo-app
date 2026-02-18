<template>
  <div>
    <header class="site-header">
      <div class="header-inner">
        <div class="logo-area">
          <span class="logo-icon">📄</span>
          <h1 class="app-title">かんたんメモ</h1>
        </div>
        <p class="app-subtitle">思いついたことをサッと記録しよう</p>
      </div>
    </header>

    <div class="app-container">
      
      <div class="card input-card">
        <h2 class="section-title">＋ 新しいメモ</h2>
        <textarea 
          v-model="newMemo" 
          placeholder="メモを入力してください...&#13;&#10;(Enterで保存、Shift+Enterで改行)"
          class="memo-input"
          rows="4"
          @keydown.enter.exact.prevent="addMemo"
        ></textarea>
        <button @click="addMemo" class="save-button">
          ＋ メモを保存
        </button>
      </div>

      <div class="list-section">
        <div class="list-header">
          <h2 class="section-title-small">📄 保存されたメモ</h2>
          <span class="count-text" v-if="memos.length > 0">{{ memos.length }}件</span>
        </div>

        <ul v-if="memos.length > 0" class="memo-list">
          <li v-for="memo in memos" :key="memo.id" class="memo-item">
            <div class="memo-content">
              <span class="memo-text" style="white-space: pre-wrap;">{{ memo.content }}</span>
              <span class="memo-date">{{ new Date(memo.created_at).toLocaleString() }}</span>
            </div>
            <button @click="deleteMemo(memo.id)" class="delete-button" title="削除">
              🗑️
            </button>
          </li>
        </ul>

        <p v-else class="empty-state">まだメモはありません。</p>
        
        <p class="hint-text">💡 Enterキーで素早く保存できます</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const memos = ref([]);
const newMemo = ref('');

// 読み込み
const fetchMemos = async () => {
  try {
    const response = await axios.get('http://localhost:48080/api/memos');
    memos.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

// 追加
const addMemo = async () => {
  // 空文字や改行だけの場合は送信しない
  if (!newMemo.value.trim()) return;
  
  try {
    await axios.post('http://localhost:48080/api/memos', {
      content: newMemo.value
    });
    newMemo.value = '';
    await fetchMemos();
  } catch (error) {
    console.error(error);
  }
};

// 削除
const deleteMemo = async (id) => {
  if (!confirm('本当に削除しますか？')) return;
  try {
    await axios.delete(`http://localhost:48080/api/memos/${id}`);
    await fetchMemos();
  } catch (error) {
    console.error(error);
  }
};

onMounted(() => {
  fetchMemos();
});
</script>

<style scoped>
/* 全体のフォント設定 */
div {
  font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', sans-serif;
  color: #333;
}

/* === ヘッダー（白帯） === */
.site-header {
  background-color: white; /* 背景を白に */
  width: 100%;
  padding: 40px 0 20px 0; /* 高さを確保 */
  box-shadow: 0 2px 10px rgba(0,0,0,0.02); /* うっすら影 */
  margin-bottom: 40px;
}
.header-inner {
  text-align: center;
}
.logo-area {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-bottom: 5px;
}
.logo-icon {
  font-size: 28px;
}
.app-title {
  color: #FF8c69; /* オレンジ */
  margin: 0;
  font-size: 32px; /* 文字を大きく */
  font-weight: bold;
}
.app-subtitle {
  color: #999;
  font-size: 14px;
  margin: 0;
}

/* === メインコンテナ === */
.app-container {
  max-width: 600px;
  margin: 0 auto 60px auto;
  padding: 0 20px;
}

/* カード共通スタイル */
.card {
  background: white;
  border-radius: 16px;
  padding: 30px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  margin-bottom: 40px;
}

/* 入力フォーム周り */
.section-title {
  font-size: 16px;
  margin-top: 0;
  margin-bottom: 15px;
  color: #555;
  font-weight: bold;
}
.memo-input {
  width: 100%;
  padding: 15px;
  border: 1px solid #eee; /* 薄い枠線 */
  border-radius: 8px;
  font-size: 16px;
  margin-bottom: 20px;
  box-sizing: border-box;
  background-color: #fcfcfc;
  resize: vertical; /* 縦方向だけリサイズ許可 */
  outline: none;
  font-family: inherit;
}
.memo-input:focus {
  border-color: #FF8c69;
  background-color: white;
}

/* 保存ボタン */
.save-button {
  width: 100%;
  padding: 14px;
  background: linear-gradient(to right, #FF9A76, #FF6B6B);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: opacity 0.2s, transform 0.1s;
  box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
}
.save-button:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}
.save-button:active {
  transform: translateY(1px);
}

/* リストセクション */
.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  padding: 0 5px;
}
.section-title-small {
  font-size: 16px;
  color: #555;
  font-weight: bold;
  margin: 0;
}
.count-text {
  color: #aaa; /* グレーに変更 */
  background: #f0f0f0;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: bold;
}

/* メモリスト */
.memo-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.memo-item {
  background: white;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.02);
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: transform 0.2s;
}
.memo-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.memo-content {
  display: flex;
  flex-direction: column;
  width: 90%; /* 幅を確保 */
}
.memo-text {
  font-size: 16px;
  color: #444;
  line-height: 1.5;
}
.memo-date {
  font-size: 11px;
  color: #bbb;
  margin-top: 8px;
}

/* 削除ボタン（ホバー時のみ表示） */
.delete-button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
  color: #ccc;
  transition: color 0.2s, opacity 0.2s;
  
  /* デフォルトで見えなくする */
  opacity: 0; 
}
.delete-button:hover {
  color: #ff4d4d;
}

/* 親要素（memo-item）にマウスが乗った時だけ、子要素（delete-button）を表示 */
.memo-item:hover .delete-button {
  opacity: 1;
}

/* その他 */
.empty-state {
  text-align: center;
  color: #aaa;
  margin-top: 40px;
  font-size: 14px;
}
.hint-text {
  text-align: center;
  font-size: 12px;
  color: #ccc;
  margin-top: 40px;
}
</style>