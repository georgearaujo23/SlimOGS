CREATE database OGS;
USE OGS;

CREATE TABLE jogador(
	id_jogador INT PRIMARY KEY AUTO_INCREMENT,
    nick_name varchar(40) NOT NULL,
    registro date NOT NULL,
    email varchar(100) NOT NULL UNIQUE,
    senha varchar(100) NOT NULL
);

CREATE TABLE token_jogador(
	id_token INT PRIMARY KEY AUTO_INCREMENT,
    token varchar(1000) NOT NULL,
    refresh_token varchar(1000) NOT NULL,
    validade datetime NOT NULL,
    id_jogador int NOT NULL,
    CONSTRAINT FK_token_jogador FOREIGN KEY (id_jogador) REFERENCES jogador(id_jogador)
);

CREATE TABLE aluno(
	id_aluno INT PRIMARY KEY AUTO_INCREMENT,
    matricula VARCHAR(20) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    id_jogador INT,
    CONSTRAINT FK_JogadorAluno FOREIGN KEY (id_jogador) REFERENCES jogador(id_jogador)
);

CREATE TABLE professor(
	id_professor INT PRIMARY KEY AUTO_INCREMENT,
    nome varchar(100) NOT NULL,
    email varchar(100) NOT NULL unique,
    senha varchar(100) NOT NULL
);

CREATE TABLE turma(
	id_turma INT PRIMARY KEY AUTO_INCREMENT,
    nome varchar(30) NOT NULL,
    id_professor INT,
    CONSTRAINT FK_Professor_turma FOREIGN KEY (id_professor) REFERENCES professor(id_professor)
);

CREATE TABLE turma_aluno(
	id_turma_aluno INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_turma INT NOT NULL,
    id_aluno INT NOT NULL,
    presencas_consecutivas int not null default 1,
    CONSTRAINT FK_turma_aluno_aluno FOREIGN KEY (id_aluno) REFERENCES aluno(id_aluno),
    CONSTRAINT FK_turma_aluno_turna FOREIGN KEY (id_turma) REFERENCES turma(id_turma)
);

CREATE TABLE frequencia(
	id_frequencia INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_turma_aluno INT NOT NULL,
    data_aula DATE NOT NULL,
    presente BIT NOT NULL DEFAULT 0,
    bonus_participacao INT NOT NULL DEFAULT 0,
    CONSTRAINT FK_frequencia_turma_aluno FOREIGN KEY (id_turma_aluno) REFERENCES turma_aluno(id_turma_aluno)
);

CREATE TABLE assunto(
	id_assunto INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    descricao VARCHAR(300) NOT NULL
);

CREATE TABLE questao(
	id_questao INT PRIMARY KEY NOT NULL auto_increment,
    enunciado VARCHAR(1000) NOT NULL,
    id_assunto INT NOT NULL,
    NIVEL INT NOT NULL DEFAULT 1,
    CONSTRAINT FK_questao_assunto FOREIGN KEY (id_assunto) REFERENCES assunto(id_assunto)
);

CREATE TABLE questao_alternativa(
	id_questao_alternativa INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    texto VARCHAR(400) NOT NULL,
    correta BIT NOT NULL DEFAULT 0,
    id_questao INT NOT NULL,
    CONSTRAINT FK_questao_alternativa_questao FOREIGN KEY (id_questao) REFERENCES questao(id_questao)
);

CREATE TABLE questao_aluno(
	id_questao_aluno INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_aluno INT NOT NULL,
    id_questao INT NOT NULL,
    id_questao_alternativa INT NOT NULL,
    CONSTRAINT FK_questao_aluno_aluno FOREIGN KEY (id_aluno) REFERENCES aluno(id_aluno),
    CONSTRAINT FK_questao_aluno_questao FOREIGN KEY (id_questao) REFERENCES questao(id_questao),
    CONSTRAINT FK_questao_aluno_questao_alternativa FOREIGN KEY (id_questao_alternativa) REFERENCES questao_alternativa(id_questao_alternativa)
    
);

CREATE TABLE tribo(
	id_tribo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    reputacao INT NOT NULL default 10,
    nivel INT NOT NULL default 1,
    nivel_sustentavel INT NOT NULL default 5,
    moedas INT NOT NULL default 10,
    id_jogador INT NOT NULL,
    nivel_sabedoria INT NOT NULL DEFAULT 0,
    experiencia INT NOT NULL default 0,
    experiencia_prox INT NOT NULL default 200,
    CONSTRAINT FK_tribo_jogador FOREIGN KEY (id_jogador) REFERENCES jogador(id_jogador)
);

CREATE TABLE estacao_tipo(
	id_estacao_tipo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome varchar(30) NOT NULL,
    descricao text NOT NULL
);

CREATE TABLE estacao(
	id_estacao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    producao INT NOT NULL default 15,
    consumo INT NOT NULL default 5,
    nivel INT NOT NULL default 1,
    experiencia INT NOT NULL default 0,
    experiencia_prox INT NOT NULL default 100,
    id_tribo INT NOT NULL,
    id_estacao_tipo INT NOT NULL,
    CONSTRAINT FK_ESTACAO_TIPO FOREIGN KEY (id_estacao_tipo) REFERENCES estacao_tipo(id_estacao_tipo),
    CONSTRAINT FK_ESTACAO_TRIBO FOREIGN KEY (id_tribo) REFERENCES tribo(id_tribo)
);

CREATE TABLE estacao_melhoria(
	id_estacao_melhoria  INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    custo_moedas INT NOT NULL,
    energia INT NOT NULL,
    comida INT NOT NULL,
    agua INT NOT NULL,
    populacao INT NOT NULL,
    sustentabilidade INT NOT NULL,
    sabedoria INT NOT NULL default 1,
    nivel INT NOT NULL,
    id_estacao_tipo INT NOT NULL,
    descricao text NOT NULL,
    nome varchar(40) NOT NULL,
    CONSTRAINT FK_ESTACAO_Melhoria_TIPO FOREIGN KEY (id_estacao_tipo) REFERENCES estacao_tipo(id_estacao_tipo)
);

CREATE TABLE estacao_melhoria_estacao(
	id_estacao_melhoria_estacao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    quantidade INT NOT NULL,
    id_estacao INT NOT NULL,
    id_estacao_melhoria INT NOT NULL,
    estaConstruindo boolean NOT NULL DEFAULT 0,
    inicioConstrucao datetime,
    finConstrucao datetime,
    horaServidor datetime default current_timestamp,
    CONSTRAINT FK_ESTACAO_melhoria_est FOREIGN KEY (id_estacao) REFERENCES estacao(id_estacao),
    CONSTRAINT FK_ESTACAO_est_melhoria FOREIGN KEY (id_estacao_melhoria) REFERENCES estacao_melhoria(id_estacao_melhoria)
);

CREATE TABLE alianca(
	id_alianca INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    nivel_sabedoria INT NOT NULL DEFAuLT 0
);

CREATE TABLE alianca_tribo(
	id_alianca_tribo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_alianca INT NOT NULl,
    id_tribo INT NOT NULl,
    data_entrada DATE NOT NULL,
    data_saida DATE,
    ativo bit not null default 1,
    CONSTRAINT FK_alianca_tribo_tribo FOREIGN KEY (id_tribo) REFERENCES tribo(id_tribo),
    CONSTRAINT FK_alianca_tribo_alianca FOREIGN KEY (id_alianca) REFERENCES alianca(id_alianca)
);

DROP TRIGGER ogs.EME_INSERT_construcao;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EME_INSERT_construcao
BEFORE INSERT  ON ogs.estacao_melhoria_estacao
FOR EACH ROW
BEGIN
	SET NEW.inicioConstrucao = utc_timestamp;
    SET NEW.fimConstrucao = DATE_ADD(utc_timestamp, INTERVAL 10 MINUTE);
END//
DELIMITER ;

DROP TRIGGER ogs.EME_UPDATE_construcao;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EME_UPDATE_construcao
BEFORE UPDATE  ON ogs.estacao_melhoria_estacao
FOR EACH ROW
BEGIN
	IF(NEW.estaConstruindo = 1 AND OLD.estaConstruindo = 0) THEN
		SET NEW.inicioConstrucao = utc_timestamp;
		SET NEW.fimConstrucao = DATE_ADD(utc_timestamp, INTERVAL 10 MINUTE);
    END IF;
END//
DELIMITER ;

DROP TRIGGER ogs.EME_INSERT_atualizarEstacaoXP;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EME_INSERT_atualizarEstacaoXP
AFTER INSERT  ON ogs.estacao_melhoria_estacao
FOR EACH ROW
BEGIN
	UPDATE ogs.estacao
    SET experiencia = experiencia  + 10
    WHERE id_estacao = NEW.id_estacao;
    
END//
DELIMITER ;


DROP TRIGGER ogs.EME_UPDATE_atualizarEstacaoXP;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EME_UPDATE_atualizarEstacaoXP
AFTER UPDATE  ON ogs.estacao_melhoria_estacao
FOR EACH ROW
BEGIN
	UPDATE ogs.estacao
    SET experiencia = experiencia  + (10* (NEW.quantidade - OLD.quantidade))
    WHERE id_estacao = NEW.id_estacao;
    
END//
DELIMITER ;

DROP TRIGGER ogs.EME_INSERT_atualizarDados;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EME_INSERT_atualizarDados
AFTER INSERT  ON ogs.estacao_melhoria_estacao
FOR EACH ROW
BEGIN
	DECLARE v_custo_moedas INT; 
    DECLARE v_energia INT; 
    DECLARE v_comida INT;
    DECLARE v_agua INT;
    DECLARE v_populacao INT;
    DECLARE v_sustentabilidade INT;
    DECLARE v_sabedoria INT;
    DECLARE v_id_tribo INT;
    
    SELECT ID_TRIBO INTO v_id_tribo
    FROM ogs.estacao
    WHERE id_estacao = NEW.id_estacao;
    
    SELECT
    custo_moedas, energia, comida, agua, populacao, sustentabilidade, sabedoria
    INTO
    v_custo_moedas, v_energia, v_comida, v_agua, v_populacao, v_sustentabilidade, v_sabedoria
    FROM ogs.estacao_melhoria
    WHERE ID_ESTACAO_MELHORIA = NEW.id_estacao_melhoria;
	
	UPDATE ogs.estacao
    SET producao = producao  + (CASE WHEN v_energia > 0 THEN v_energia ELSE 0 END),
    consumo = consumo  + (CASE WHEN v_energia < 0 THEN v_energia * -1 ELSE 0 END)
    WHERE id_tribo = v_id_tribo  AND id_estacao_tipo = 4;
    
    UPDATE ogs.estacao
    SET producao = producao  + (CASE WHEN v_sabedoria > 0 THEN v_sabedoria ELSE 0 END),
    consumo = consumo  + (CASE WHEN v_sabedoria < 0 THEN v_sabedoria * -1 ELSE 0 END)
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 3;
    
    UPDATE ogs.estacao
    SET producao = producao  + (CASE WHEN v_comida > 0 THEN v_comida ELSE 0 END),
    consumo = consumo  + (CASE WHEN v_comida < 0 THEN v_comida * -1 ELSE 0 END)
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 2;
    
    UPDATE ogs.estacao
    SET producao = producao  + (CASE WHEN v_agua > 0 THEN v_agua ELSE 0 END),
    consumo = consumo  + (CASE WHEN v_agua < 0 THEN v_agua * -1 ELSE 0 END)
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 1;
    
    UPDATE ogs.tribo
    SET moedas = v_custo_moedas,
    reputacao = reputacao + v_populacao,
    nivel_sabedoria = nivel_sabedoria + v_sabedoria,
    nivel_sustentavel = nivel_sustentavel + v_sustentabilidade 
    WHERE id_tribo = v_id_tribo;
    
END//
DELIMITER ;

DROP TRIGGER ogs.EME_UPDATE_atualizarDados;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EME_UPDATE_atualizarDados
AFTER UPDATE  ON ogs.estacao_melhoria_estacao
FOR EACH ROW
BEGIN
	DECLARE v_custo_moedas INT; 
    DECLARE v_energia INT; 
    DECLARE v_comida INT;
    DECLARE v_agua INT;
    DECLARE v_populacao INT;
    DECLARE v_sustentabilidade INT;
    DECLARE v_sabedoria INT;
    DECLARE v_id_tribo INT;
    
    SELECT ID_TRIBO INTO v_id_tribo
    FROM ogs.estacao
    WHERE id_estacao = NEW.id_estacao;
    
    SELECT
    custo_moedas, energia, comida, agua, populacao, sustentabilidade, sabedoria
    INTO
    v_custo_moedas, v_energia, v_comida, v_agua, v_populacao, v_sustentabilidade, v_sabedoria
    FROM ogs.estacao_melhoria
    WHERE ID_ESTACAO_MELHORIA = NEW.id_estacao_melhoria;
	
	UPDATE ogs.estacao
    SET producao = producao  + ((CASE WHEN v_energia > 0 THEN v_energia ELSE 0 END)* (NEW.quantidade - OLD.quantidade)),
    consumo = consumo  + ((CASE WHEN v_energia < 0 THEN v_energia * -1 ELSE 0 END)* (NEW.quantidade - OLD.quantidade))
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 4;
    
    UPDATE ogs.estacao
    SET producao = producao  + ((CASE WHEN v_sabedoria > 0 THEN v_sabedoria ELSE 0 END)* (NEW.quantidade - OLD.quantidade)),
    consumo = consumo  + ((CASE WHEN v_sabedoria < 0 THEN v_sabedoria * -1 ELSE 0 END)* (NEW.quantidade - OLD.quantidade))
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 3;
    
    UPDATE ogs.estacao
    SET producao = producao  + ((CASE WHEN v_comida > 0 THEN v_comida ELSE 0 END)* (NEW.quantidade - OLD.quantidade)),
    consumo = consumo  + ((CASE WHEN v_comida < 0 THEN v_comida * -1 ELSE 0 END)* (NEW.quantidade - OLD.quantidade))
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 2;
    
    UPDATE ogs.estacao
    SET producao = producao  + ((CASE WHEN v_agua > 0 THEN v_agua ELSE 0 END)* (NEW.quantidade - OLD.quantidade)),
    consumo = consumo  + ((CASE WHEN v_agua < 0 THEN v_agua * -1 ELSE 0 END)* (NEW.quantidade - OLD.quantidade))
    WHERE id_tribo = v_id_tribo AND id_estacao_tipo = 1;
    
    UPDATE ogs.tribo
    SET moedas = moedas - (v_custo_moedas* (NEW.quantidade - OLD.quantidade)),
    reputacao = reputacao + (v_populacao* (NEW.quantidade - OLD.quantidade)),
    nivel_sabedoria = nivel_sabedoria + (v_sabedoria* (NEW.quantidade - OLD.quantidade)),
    nivel_sustentavel = nivel_sustentavel + (v_sustentabilidade* (NEW.quantidade - OLD.quantidade)) 
    WHERE id_tribo = v_id_tribo;
    
END//
DELIMITER ;

DROP TRIGGER ogs.EST_UPDATE_atualizarTriboXP;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.EST_UPDATE_atualizarTriboXP
BEFORE UPDATE  ON ogs.estacao
FOR EACH ROW
BEGIN
	if (NEW.experiencia >= NEW.experiencia_prox) THEN
		SET NEW.nivel = NEW.nivel + 1;
        SET NEW.experiencia_prox = OLD.experiencia_prox * 2;
    END IF;
    
    UPDATE ogs.tribo
    SET experiencia = experiencia + (NEW.experiencia - OLD.experiencia)
    WHERE id_tribo = NEW.id_tribo;
    
END//
DELIMITER ;

DROP TRIGGER ogs.TRI_UPDATE_atualizarTriboNivel;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.TRI_UPDATE_atualizarTriboNivel
BEFORE UPDATE  ON ogs.tribo
FOR EACH ROW
BEGIN
	IF (NEW.experiencia >= NEW.experiencia_prox) THEN
		SET NEW.nivel = NEW.nivel + 1;
        SET NEW.experiencia_prox = OLD.experiencia_prox * 3;
    END IF;
    
END//
DELIMITER ;

DROP TRIGGER ogs.atualizarBonificacao;
DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER ogs.atualizarBonificacao
AFTER INSERT ON ogs.frequencia
FOR EACH ROW
BEGIN
	DECLARE v_id_jogador INT;
    SET v_id_jogador = (
						SELECT MAX(id_jogador) 
                        FROM ogs.jogador j
                        INNER JOIN ogs.aluno a ON a.id_jogador = j.id_jogador
							INNER JOIN ogs.turma_aluno ta ON ta.id_aluno = a.id_aluno
						WHERE ta.id_turma_aluno = new.id_turma_aluno
                        );
	UPDATE ogs.tribo
    SET moedas = moedas  + NEW.bonus_participacao,
    experiencia = experiencia + 10,
    nivel_sabedoria = nivel_sabedoria + 10
    WHERE id_jogador = v_id_jogador;
    
    UPDATE ogs.turma_aluno
    SET presencas_consecutivas = (CASE NEW.presente WHEN 1 THEN presencas_consecutivas + 1 ELSE 0 END)
    WHERE id_turma_aluno = new.id_turma_aluno;
    
    
END//
DELIMITER ;

DROP TRIGGER ogs.atualizarBonusPresenca;
DELIMITER //
CREATE  DEFINER = CURRENT_USER TRIGGER ogs.atualizarBonusPresenca
AFTER UPDATE ON ogs.turma_aluno
FOR EACH ROW
BEGIN
	DECLARE v_id_jogador INT;
	IF MOD(NEW.presencas_consecutivas, 2) = 0 AND NEW.presencas_consecutivas > 0 AND  NEW.presencas_consecutivas <> OLD.presencas_consecutivas THEN
		SET v_id_jogador = (
							SELECT MAX(id1_jogador) 
							FROM ogs.jogador j
							INNER JOIN ogs.aluno a ON a.id_jogador = j.id_jogador
							WHERE a.id_aluno = NEW.id_aluno
							);
		
		UPDATE ogs.tribo
		SET moedas = moedas + 10,
        sabedoria = sabedoria + (NEW.presencas_consecutivas/2),
        energia = energia + (NEW.presencas_consecutivas/2),
        agua = agua + (NEW.presencas_consecutivas/2),
        comida = comida + (NEW.presencas_consecutivas/2)
		WHERE id_jogador = nv_id_jogador;
    END IF;
    
END//
DELIMITER ;

Grant SELECT, DELETE, UPDATE, INSERT ON ogs.aluno TO game@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.jogador TO game@localhost;
Grant SELECT ON ogs.assunto TO game@localhost;
Grant SELECT ON ogs.questao TO game@localhost;
Grant SELECT ON ogs.questao_alternativa TO game@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.questao_aluno TO game@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.turma_aluno TO game@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.tribo TO game@localhost;
Grant SELECT ON ogs.turma TO game@localhost;

Grant SELECT, DELETE, UPDATE, INSERT ON ogs.aluno TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.jogador TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.assunto TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.questao TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.questao_alternativa TO web@localhost;
Grant SELECT ON ogs.questao_aluno TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.turma_aluno TO web@localhost;
Grant SELECT ON ogs.tribo TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.turma TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.professor TO web@localhost;
Grant SELECT, DELETE, UPDATE, INSERT ON ogs.frequencia TO web@localhost;