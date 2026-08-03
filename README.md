# 🚀 Projeto Integrador – Disciplina: Projeto e Implementação de Sistemas para Web II

Bem-vindos ao repositório base do Projeto Integrador! Este documento reúne todas as diretrizes, requisitos mínimos, cronograma de entregas e critérios de avaliação necessários para o desenvolvimento do projeto ao longo do semestre.

---

## 📅 Apresentação e Dinâmica de Trabalho
O objetivo do Projeto Integrador é proporcionar uma experiência prática de desenvolvimento de aplicações web completas e funcionais, aplicando de forma incremental os conhecimentos adquiridos na disciplina.

* **Formação de Equipes:** O projeto deve ser desenvolvido em duplas (ou individualmente, conforme autorização), definidas nas semanas iniciais.
* **Desenvolvimento Incremental:** O sistema será construído gradualmente. Cada entrega parcial servirá como base direta para a etapa seguinte.
* **Temas Livres:** As equipes têm liberdade para escolher o escopo do sistema (Ex: Controle de Estoque, Gestão Escolar, Clínicas, Eventos, Finanças), desde que validado previamente com o professor.

---

## 🛠️ Tecnologias Obrigatórias
Para a construção do ecossistema de software, todas as equipes deverão utilizar estritamente o seguinte *tech stack*:

* **Backend:** PHP (Orientado a Objetos).
* **Arquitetura:** MVC (*Model-View-Controller*).
* **Banco de Dados:** PostgreSQL ou MySQL.
* **Controle de Versão:** Git & GitHub.
* **Ambiente Local:** Docker, XAMPP ou WampServer.

---

## 📋 Requisitos Mínimos do Sistema
Independentemente do tema escolhido, a versão final da aplicação deverá conter:

- [ ] Arquitetura MVC devidamente isolada e estruturada.
- [ ] Mecanismo de persistência conectado via PDO a um Banco de Dados Relacional.
- [ ] Sistema próprio de gerenciamento de Rotas amigáveis.
- [ ] Operações CRUD completas para a entidade principal do escopo.
- [ ] Filtros e Validação robusta de formulários.
- [ ] Sistema de Autenticação completo (*Login* e *Logout*).
- [ ] Controle de Sessões ativo e persistência de estado segura.
- [ ] Controle de acesso baseado em Perfis (Mínimo: *Administrador* e *Usuário Comum*).
- [ ] Funcionalidade de manipulação e *upload* de arquivos.
- [ ] Mecanismos adequados de tratamento de erros, exceções e segurança de dados.
- [ ] Interface web responsiva, amigável e funcional.
- [ ] *Deploy* da aplicação realizado em servidor ou ambiente de nuvem público.

---

## ⏱️ Cronograma e Pontuação das Entregas

| Etapa | Foco da Entrega | Prazo Estimado |
| :--- | :--- | :--- | :--- |
| **Entrega Parcial 1** | Planejamento, Modelagem (MER/DER) e Protótipos | Semana 3 |
| **Entrega Parcial 2** | Estrutura MVC Inicial e Sistema de Rotas | Semana 5 |
| **Entrega Parcial 3** | Conexão de Banco de Dados (PDO) e CRUD Inicial (C e R) | Semana 7 |
| **Entrega Parcial 4** | CRUD Completo da Entidade (C, R, U, D) e Validações | Semana 9 |
| **Entrega Parcial 5** | Sessões, Autenticação de Usuários e Níveis de Acesso | Semana 12 |
| **Projeto Final** | Sistema Completo, Documentação (*Manual*) e Vídeo de Demonstração | Semana 16 |

---

## ⚖️ Critérios Gerais de Avaliação

A nota final do ecossistema de software desenvolvido considerará a seguinte distribuição de pesos técnicos 383]:

* **Funcionalidade da Aplicação (40%):** O sistema cumpre o escopo proposto sem falhas técnicas ou bugs impeditivos? 
* **Arquitetura MVC (20%):** Há separação estrita de responsabilidades entre as camadas de controle, dados e visualização? 
* **Banco de Dados (15%):** A modelagem física atende à terceira forma normal? As consultas via PDO utilizam boas práticas contra injeção de código? 
* **Segurança (10%):** Senhas estão devidamente hasheadas? Rotas sensíveis estão protegidas contra acessos não autenticados? 
* **Interface e Usabilidade (5%):** A experiência do usuário é fluida, clara e com tratamento visual agradável? 
* **Documentação (10%):** O projeto possui manuais claros e código bem estruturado? 

---

## ⚠️ Regras Essenciais do Repositório

> 🛑 **Atenção:**

> 1. **Histórico de Commits:** Todas as entregas são cumulativas. O código-fonte deve ser mantido e atualizado obrigatoriamente neste repositório durante todo o semestre acadêmico.

> 2. **Evolução Contínua:** Repositórios estagnados que apresentarem atualizações massivas apenas em datas de entrega sem histórico de evolução orgânica estarão sujeitos a severas penalizações na nota.

> 3. **Prazo Extrapolado:** Entregas atrasadas estarão sujeitas aos fatores de desconto previamente estipulados em contrato pedagógico.
