const express = require('express');
const { Pool } = require('pg');

const app = express();
app.use(express.json());

// Configuração de conexão utilizando variáveis de ambiente
// Repare que o HOST padrão é o NOME do container do banco ('db-postgres')
const pool = new Pool({
  host: process.env.DB_HOST || 'db-postgres',
  port: process.env.DB_PORT || 5432,
  user: process.env.DB_USER || 'usuario_app',
  password: process.env.DB_PASSWORD || 'senha_extremamente_segura',
  database: process.env.DB_NAME || 'meubanco',
});

// Inicialização: Cria a tabela automaticamente se não existir
async function initDb() {
  try {
    await pool.query(`
      CREATE TABLE IF NOT EXISTS visitas (
        id SERIAL PRIMARY KEY,
        data TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    `);
    console.log('Tabela no PostgreSQL verificada/criada com sucesso!');
  } catch (err) {
    console.error('Erro ao conectar ou inicializar o Banco de Dados:', err);
  }
}

// Rota 1: Registra uma nova visita no banco
app.post('/visita', async (req, res) => {
  try {
    const result = await pool.query('INSERT INTO visitas DEFAULT VALUES RETURNING *');
    res.status(201).json({ mensagem: 'Visita registrada!', registro: result.rows[0] });
  } catch (err) {
    res.status(500).json({ erro: err.message });
  }
});

// Rota 2: Lista todas as visitas salvas
app.get('/visitas', async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM visitas');
    res.json({ total: result.rowCount, visitas: result.rows });
  } catch (err) {
    res.status(500).json({ erro: err.message });
  }
});

app.listen(3000, () => {
  console.log('API executando na porta 3000');
  initDb();
});
