# 🧭 Guia Simples: Como Baixar, Alterar e Enviar um Projeto no GitHub

Este guia ensina passo a passo, de forma fácil, como **baixar um projeto**, **editar**, **enviar as alterações** e **fazer o merge** no GitHub.  
Ideal para quem está começando!

---

## 💡 O que você vai aprender
1. Clonar (baixar) o projeto
2. Criar uma nova branch (ramo de trabalho)
3. Fazer commits (salvar mudanças)
4. Enviar para o GitHub (push)
5. Criar um Pull Request e fazer o merge

---

## 🚀 1. Clonando o projeto

### ✅ Pré-requisitos:
- Ter o **Git** instalado.  
👉 [Baixar Git](https://git-scm.com/downloads)

### 🔹 Passos:
Abra o terminal ou Git Bash e digite:

```bash
git clone https://github.com/SEU-USUARIO/NOME-DO-PROJETO.git
```

Entre na pasta do projeto:

```bash
cd NOME-DO-PROJETO
```

---

## 🌿 2. Criando uma nova branch

A **branch** é um espaço separado para você trabalhar sem alterar o código principal.

```bash
git checkout -b nome-da-branch
```

Exemplo:
```bash
git checkout -b ajuste-login
```
Trocando de Branch
git switch nome-da-sua-branch
---

## 💾 3. Fazendo alterações e commitando

Edite os arquivos do projeto (HTML, CSS, JS, etc).  
Depois, salve as alterações com:

```bash
git add .
git commit -m "Mensagem explicando o que foi alterado"
```

Exemplo:
```bash
git commit -m "Corrigido botão de login"
```

---

## ☁️ 4. Enviando para o GitHub (Push)

```bash
git push origin nome-da-branch
```

Exemplo:
```bash
git push origin feature/ajuste-login
```

➡ Isso envia suas alterações para o repositório remoto no GitHub.

---

## 🔁 5. Criando o Pull Request (PR)

1. Vá até o repositório no GitHub.  
2. Clique em **Compare & pull request**.  
3. Escreva uma breve descrição da sua alteração.  
4. Clique em **Create pull request**.

---

## ✅ 6. Fazendo o merge (juntando com a main)

Se você tiver permissão para aprovar a PR:

1. Abra o Pull Request criado.  
2. Clique em **Merge pull request**.  
3. Confirme com **Confirm merge**.  
4. Pronto! 🎉 Seu código foi juntado ao projeto principal (lembre-se de indicar a branch para -> main).

---

## 🔍 Exemplo completo de uso

```bash
git clone https://github.com/Nayara95/NexusFitness.git
cd NexusFitness
git checkout -b feature/Nome-da-minha-branch
# (faça suas alterações no projeto)
git add .
git commit -m "Ajustado layout do login"
git push origin ajuste-login
```

Depois vá até o GitHub → Crie o Pull Request → Faça o Merge Manualmente. ✅

---

✳️ **Pronto!** Agora você já sabe colaborar com projetos no GitHub. 🚀
