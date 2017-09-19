/*CONTRIBUIÇÃO DE ANDREUS TIMM*/
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ipi_enquadramento` (
  `id_ipi_enquadramento` INT(16) NOT NULL AUTO_INCREMENT ,
  `id_ipi_operacao` INT(16) NOT NULL ,
  `codigo_ipi_enquadramento` VARCHAR(4) NOT NULL ,
  `desc_ipi_enquadramento` TEXT NOT NULL ,
  PRIMARY KEY (`id_ipi_enquadramento`) ,
  INDEX `fk_ipi_enquadramento_ipi_operacao1_idx` (`id_ipi_operacao` ASC) ,
  CONSTRAINT `fk_ipi_enquadramento_ipi_operacao1`
    FOREIGN KEY (`id_ipi_operacao` )
    REFERENCES `ipi_operacao` (`id_ipi_operacao` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

TRUNCATE TABLE `ipi_enquadramento`;

INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (1, '999', 'Tributação normal IPI; Outros');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '301', 'Produtos industrializados por instituições de educação ou de assistência social, destinados a uso próprio ou a distribuição gratuita a seus educandos ou assistidos - Art. 54 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '302', 'Produtos industrializados por estabelecimentos públicos e autárquicos da União, dos Estados, do Distrito Federal e dos Municípios, não destinados a comércio - Art. 54 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '303', 'Amostras de produtos para distribuição gratuita, de diminuto ou nenhum valor comercial - Art. 54 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '304', 'Amostras de tecidos sem valor comercial - Art. 54 Inciso IV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '305', 'Pés isolados de calçados - Art. 54 Inciso V do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '306', 'Aeronaves de uso militar e suas partes e peças, vendidas à União - Art. 54 Inciso VI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '307', 'Caixões funerários - Art. 54 Inciso VII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '308', 'Papel destinado à impressão de músicas - Art. 54 Inciso VIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '309', 'Panelas e outros artefatos semelhantes, de uso doméstico, de fabricação rústica, de pedra ou barro bruto - Art. 54 Inciso IX do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '310', 'Chapéus, roupas e proteção, de couro, próprios para tropeiros - Art. 54 Inciso X do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '311', 'Material bélico, de uso privativo das Forças Armadas, vendido à União - Art. 54 Inciso XI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '312', 'Automóvel adquirido diretamente a fabricante nacional, pelas missões diplomáticas e repartições consulares de caráter permanente, ou seus integrantes, bem assim pelas representações internacionais ou regionais de que o Brasil seja membro, e seus funcionários, peritos, técnicos e consultores, de nacionalidade estrangeira, que exerçam funções de caráter permanente - Art. 54 Inciso XII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '313', 'Veículo de fabricação nacional adquirido por funcionário das missões diplomáticas acreditadas junto ao Governo Brasileiro - Art. 54 Inciso XIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '314', 'Produtos nacionais saídos diretamente para Lojas Francas - Art. 54 Inciso XIV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '315', 'Materiais e equipamentos destinados a Itaipu Binacional - Art. 54 Inciso XV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '316', 'Produtos Importados por missões diplomáticas, consulados ou organismo internacional - Art. 54 Inciso XVI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '317', 'Bagagem de passageiros desembaraçada com isenção do II. - Art. 54 Inciso XVII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '318', 'Bagagem de passageiros desembaraçada com pagamento do II. - Art. 54 Inciso XVIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '319', 'Remessas postais internacionais sujeitas a tributação simplificada. - Art. 54 Inciso XIX do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '320', 'Máquinas e outros destinados à pesquisa científica e tecnológica - Art. 54 Inciso XX do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '321', 'Produtos de procedência estrangeira, isentos do II conforme Lei nº 8032/1990. - Art. 54 Inciso XXI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '322', 'Produtos de procedência estrangeira utilizados em eventos esportivos - Art. 54 Inciso XXII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '323', 'Veículos automotores, máquinas, equipamentos, bem assim suas partes e peças separadas, destinadas à utilização nas atividades dos Corpos de Bombeiros - Art. 54 Inciso XXIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '324', 'Produtos importados para consumo em congressos, feiras e exposições - Art. 54 Inciso XXIV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '325', 'Bens de informática, Matéria Prima, produtos intermediários e embalagem destinados a Urnas eletrônicas - TSE - Art. 54 Inciso XXV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '326', 'Materiais, equipamentos, máquinas, aparelhos e instrumentos, bem assim os respectivos acessórios, sobressalentes e ferramentas, que os acompanhem, destinados à construção do Gasoduto Brasil - Bolívia - Art. 54 Inciso XXVI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '327', 'Partes, peças e componentes, adquiridos por estaleiros navais brasileiros, destinados ao emprego na conservação, modernização, conversão ou reparo de embarcações registradas no Registro Especial Brasileiro - REB - Art. 54 Inciso XXVII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '328', 'Aparelhos transmissores e receptores de radiotelefonia e radiotelegrafia; veículos para patrulhamento policial; armas e munições, destinados a órgãos de segurança pública da União, dos Estados e do Distrito Federal - Art. 54 Inciso XXVIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '329', 'Automóveis de passageiros de fabricação nacional destinados à utilização como táxi adquiridos por motoristas profissionais - Art. 55 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '330', 'Automóveis de passageiros de fabricação nacional destinados à utilização como táxi por impedidos de exercer atividade por destruição, furto ou roubo do veículo adquiridos por motoristas profissionais. - Art. 55 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '331', 'Automóveis de passageiros de fabricação nacional destinados à utilização como táxi adquiridos por cooperativas de trabalho. - Art. 55 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '332', 'Automóveis de passageiros de fabricação nacional, destinados a pessoas portadoras de deficiência física, visual, mental severa ou profunda, ou autistas - Art. 55 Inciso IV do Nota Fiscal eletrônica Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '333', 'Produtos estrangeiros, recebidos em doação de representações diplomáticas estrangeiras sediadas no País, vendidos em feiras, bazares e eventos semelhantes por entidades beneficentes - Art. 67 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '334', 'Produtos industrializados na Zona Franca de Manaus - ZFM, destinados ao seu consumo interno - Art. 81 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '335', 'Produtos industrializados na ZFM, por estabelecimentos com projetos aprovados pela SUFRAMA, destinados a comercialização em qualquer outro ponto do Território Nacional - Art. 81 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '336', 'Produtos nacionais destinados à entrada na ZFM, para seu consumo interno, utilização ou industrialização, ou ainda, para serem remetidos, por intermédio de seus entrepostos, à Amazônia Ocidental - Art. 81 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '337', 'Produtos industrializados por estabelecimentos com projetos aprovados pela SUFRAMA, consumidos ou utilizados na Amazônia Ocidental, ou adquiridos através da ZFM ou de seus entrepostos na referida região - Art. 95 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '338', 'Produtos de procedência estrangeira, relacionados na legislação, oriundos da ZFM e que derem entrada na Amazônia Ocidental para ali serem consumidos ou utilizados: - Art. 95 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '339', 'Produtos elaborados com matérias-primas agrícolas e extrativas vegetais de produção regional, por estabelecimentos industriais localizados na Amazônia Ocidental, com projetos aprovados pela SUFRAMA - Art. 95 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '340', 'Produtos industrializados em Área de Livre Comércio - Art. 105 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '341', 'Produtos nacionais ou nacionalizados, destinados à entrada na Área de Livre Comércio de Tabatinga - ALCT - Art. 107 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '342', 'Produtos nacionais ou nacionalizados, destinados à entrada na Área de Livre Comércio de Guajará-Mirim - ALCGM - Art. 110 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '343', 'Produtos nacionais ou nacionalizados, destinados à entrada nas Áreas de Livre Comércio de Boa Vista - ALCBV e Bonfim - ALCB - Art. 113 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '344', 'Produtos nacionais ou nacionalizados, destinados à entrada na Área de Livre Comércio de Macapá e Santana - ALCMS - Art. 117 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '345', 'Produtos nacionais ou nacionalizados, destinados à entrada nas Áreas de Livre Comércio de Brasiléia - ALCB e de Cruzeiro do Sul - ALCCS - Art. 120 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '346', 'Recompe - equipamentos de informática - de beneficiário do regime para escolas das redes públicas de ensino federal, estadual, distrital, municipal ou nas escolas sem fins lucrativos de atendimento a pessoas com deficiência - Decreto nº 7.243/2010, art. 7º');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '347', 'Rio 2016 - Importação de materiais para os jogos (medalhas, troféus, impressos, bens não duráveis, etc.) - Lei nº 12.780/2013, Art. 4º, §1º, I');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '348', 'Rio 2016 - Suspensão convertida em Isenção - Lei nº 12.780/2013, Art. 6º, I');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '349', 'Rio 2016 - Empresas vinculadas ao CIO - Lei nº 12.780/2013, Art. 9º, I, d');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '350', 'Rio 2016 - Saída de produtos importados pelo RIO 2016 - Lei nº 12.780/2013, Art. 10, I, d');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (2, '351', 'Rio 2016 - Produtos nacionais, não duráveis, uso e consumo dos eventos, adquiridos pelas pessoas jurídicas mencionadas no § 2o do art. 4o da Lei nº 12.780/2013 - Lei nº 12.780/2013, Art. 12');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '001', 'Livros, jornais, periódicos e o papel destinado à sua impressão - Art. 18 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '002', 'Produtos industrializados destinados ao exterior - Art. 18 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '003', 'Ouro, definido em lei como ativo financeiro ou instrumento cambial - Art. 18 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '004', 'Energia elétrica, derivados de petróleo, combustíveis e minerais do País - Art. 18 Inciso IV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '005', 'Exportação de produtos nacionais - sem saída do território brasileiro - venda para empresa sediada no exterior - atividades de pesquisa ou lavra de jazidas de petróleo e de gás natural - Art. 19 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '006', 'Exportação de produtos nacionais - sem saída do território brasileiro - venda para empresa sediada no exterior - incorporados a produto final exportado para o Brasil - Art. 19 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (3, '007', 'Exportação de produtos nacionais - sem saída do território brasileiro - venda para órgão ou entidade de governo estrangeiro ou organismo internacional de que o Brasil seja membro, para ser entregue, no País, à ordem do comprador - Art. 19 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '101', 'Óleo de menta em bruto, produzido por lavradores - Art. 43 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '102', 'Produtos remetidos à exposição em feiras de amostras e promoções semelhantes - Art. 43 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '103', 'Produtos remetidos a depósitos fechados ou armazéns-gerais, bem assim aqueles devolvidos ao remetente - Art. 43 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '104', 'Produtos industrializados, que com matérias-primas (MP), produtos intermediários (PI) e material de embalagem (ME) importados submetidos a regime aduaneiro especial (drawback - suspensão/isenção), remetidos diretamente a empresas industriais exportadoras - Art. 43 Inciso IV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '105', 'Produtos, destinados à exportação, que saiam do estabelecimento industrial para empresas comerciais exportadoras, com o fim específico de exportação - Art. 43, Inciso V, alínea “a” do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '106', 'Produtos, destinados à exportação, que saiam do estabelecimento industrial para recintos alfandegados onde se processe o despacho aduaneiro de exportação - Art. 43, Inciso V, alíneas “b” do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '107', 'Produtos, destinados à exportação, que saiam do estabelecimento industrial para outros locais onde se processe o despacho aduaneiro de exportação - Art. 43, Inciso V, alíneas “c” do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '108', 'Matérias-primas (MP), produtos intermediários (PI) e material de embalagem (ME) destinados ao executor de industrialização por encomenda - Art. 43 Inciso VI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '109', 'Produtos industrializados por encomenda remetidos ao estabelecimento de origem - Art. 43 Inciso VII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '110', 'Matérias-primas ou produtos intermediários remetidos para emprego em operação industrial realizada pelo remetente fora do estabelecimento - Art. 43 Inciso VIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '111', 'Veículo, aeronave ou embarcação destinados a emprego em provas de engenharia pelo fabricante - Art. 43 Inciso IX do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '112', 'Produtos remetidos, para industrialização ou comércio, de um para outro estabelecimento da mesma firma - Art. 43 Inciso X do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '113', 'Bens do ativo permanente remetidos a outro estabelecimento da mesma firma, para serem utilizados no processo industrial do recebedor - Art. 43 Inciso XI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '114', 'Bens do ativo permanente remetidos a outro estabelecimento, para serem utilizados no processo industrial de produtos encomendados pelo remetente - Art. 43 Inciso XII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '115', 'Partes e peças destinadas ao reparo de produtos com defeito de fabricação, quando a operação for executada gratuitamente, em virtude de garantia - Art. 43 Inciso XIII do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '116', 'Matérias-primas (MP), produtos intermediários (PI) e material de embalagem (ME) de fabricação nacional, vendidos a estabelecimento industrial, para industrialização de produtos destinados à exportação ou a estabelecimento comercial, para industrialização em outro estabelecimento da mesma firma ou de terceiro, de produto destinado à exportação - Nota Fiscal eletrônica Art. 43 Inciso XIV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '117', 'Produtos para emprego ou consumo na industrialização ou elaboração de produto a ser exportado, adquiridos no mercado interno ou importados - Art. 43 Inciso XV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '118', 'Bebidas alcóolicas e demais produtos de produção nacional acondicionados em recipientes de capacidade superior ao limite máximo permitido para venda a varejo - Art. 44 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '119', 'Produtos classificados NCM 21.06.90.10 Ex 02, 22.01, 22.02, exceto os Ex 01 e Ex 02 do Código 22.02.90.00 e 22.03 saídos de estabelecimento industrial destinado a comercial equiparado a industrial - Art. 45 Inciso I do Decreto7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '120', 'Produtos classificados NCM 21.06.90.10 Ex 02, 22.01, 22.02, exceto os Ex 01 e Ex 02 do Código 22.02.90.00 e 22.03 saídos de estabelecimento comercial equiparado a industrial destinado a equiparado a industrial - Art. 45 Inciso II do Decreto7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '121', 'Produtos classificados NCM 21.06.90.10 Ex 02, 22.01, 22.02, exceto os Ex 01 e Ex 02 do Código 22.02.90.00 e 22.03 saídos de importador destinado a equiparado a industrial - Art. 45 Inciso III do Decreto7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '122', 'Matérias-primas (MP), produtos intermediários (PI) e material de embalagem (ME) destinados a estabelecimento que se dedique à elaboração de produtos classificados nos códigos previstos no art. 25 da Lei 10.684/2003 - Art. 46 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '123', 'Matérias-primas (MP), produtos intermediários (PI) e material de embalagem (ME) adquiridos por estabelecimentos industriais fabricantes de partes e peças destinadas a estabelecimento industrial fabricante de produto classificado no Capítulo 88 da Tipi - Art. 46 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '124', 'Matérias-primas (MP), produtos intermediários (PI) e material de embalagem (ME) adquiridos por pessoas jurídicas preponderantemente exportadoras - Art. 46 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '125', 'Materiais e equipamentos destinados a embarcações pré-registradas ou registradas no Registro Especial Brasileira - REB quando adquiridos por estaleiros navais brasileiros - Art. 46 Inciso IV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '126', 'Aquisição por beneficiário de regime aduaneiro suspensivo do imposto, destinado a industrialização para exportação - Art. 47 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '127', 'Desembaraço de produtos de procedência estrangeira importados por lojas francas - Art. 48 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '128', 'Desembaraço de maquinas, equipamentos, veículos, aparelhos e instrumentos sem similar nacional importados por empresas nacionais de engenharia, destinados à execução de obras no exterior - Art. 48 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '129', 'Desembaraço de produtos de procedência estrangeira com saída de repartições aduaneiras com suspensão do Imposto de Importação - Art. 48 Inciso III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '130', 'Desembaraço de matérias-primas, produtos intermediários e materiais de embalagem, importados diretamente por estabelecimento de que tratam os incisos I a III do caput do Decreto 7.212/2010 - Art. 48 Inciso IV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '131', 'Remessa de produtos para a ZFM destinados ao seu consumo interno, utilização ou industrialização - Art. 84 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '132', 'Remessa de produtos para a ZFM destinados à exportação - Art. 85 Inciso I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '133', 'Produtos que, antes de sua remessa à ZFM, forem enviados pelo seu fabricante a outro estabelecimento, para industrialização adicional, por conta e ordem do destinatário - Art. 85 Inciso II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '134', 'Desembaraço de produtos de procedência estrangeira importados pela ZFM quando ali consumidos ou utilizados, exceto armas, munições, fumo, bebidas alcoólicas e automóveis de passageiros. - Art. 86 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '135', 'Remessa de produtos para a Amazônia Ocidental destinados ao seu consumo interno ou utilização - Art. 96 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '136', 'Entrada de produtos estrangeiros na Área de Livre Comércio de Tabatinga - ALCT destinados ao seu consumo interno ou utilização - Art. 106 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '137', 'Entrada de produtos estrangeiros na Área de Livre Comércio de Guajará-Mirim - ALCGM destinados ao seu consumo interno ou utilização - Art. 109 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '138', 'Entrada de produtos estrangeiros nas Áreas de Livre Comércio de Boa Vista - ALCBV e Bomfim - ALCB destinados a seu consumo interno ou utilização - Art. 112 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '139', 'Entrada de produtos estrangeiros na Área de Livre Comércio de Macapá e Santana - ALCMS destinados a seu consumo interno ou utilização - Art. 116 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '140', 'Entrada de produtos estrangeiros nas Áreas de Livre Comércio de Brasiléia - ALCB e de Cruzeiro do Sul - ALCCS destinados a seu consumo interno ou utilização - Art. 119 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '141', 'Remessa para Zona de Processamento de Exportação - ZPE - Art. 121 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '142', 'Setor Automotivo - Desembaraço aduaneiro, chassis e outros - regime aduaneiro especial - industrialização 87.01 a 87.05 - Art. 136, I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '143', 'Setor Automotivo - Do estabelecimento industrial produtos 87.01 a 87.05 da TIPI - mercado interno - empresa comercial atacadista controlada por PJ encomendante do exterior. - Art. 136, II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '144', 'Setor Automotivo - Do estabelecimento industrial - chassis e outros classificados nas posições 84.29, 84.32, 84.33, 87.01 a 87.06 e 87.11 da TIPI. - Art. 136, III do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '145', 'Setor Automotivo - Desembaraço aduaneiro, chassis e outros classificados nas posições 84.29, 84.32, 84.33, 87.01 a 87.06 e 87.11 da TIPI quando importados diretamente por estabelecimento industrial - Art. 136, IV do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '146', 'Setor Automotivo - do estabelecimento industrial matérias-primas, os produtos intermediários e os materiais de embalagem, adquiridos por fabricantes, preponderantemente, de componentes, chassis e outros classificados nos Códigos 84.29, 8432.40.00, 8432.80.00, 8433.20, 8433.30.00, 8433.40.00, 8433.5 e 87.01 a 87.06 da TIPI - Art. 136, V do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '147', 'Setor Automotivo - Desembaraço aduaneiro, as matérias-primas, os produtos intermediários e os materiais de embalagem, importados diretamente por fabricantes, preponderantemente, de componentes, chassis e outros classificados nos Códigos 84.29, 8432.40.00, 8432.80.00, 8433.20, 8433.30.00, 8433.40.00, 8433.5 e 87.01 a 87.06 da TIPI - Art. 136, VI do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '148', 'Bens de Informática e Automação - matérias-primas, os produtos intermediários e os materiais de embalagem, quando adquiridos por estabelecimentos industriais fabricantes dos referidos bens. - Art. 148 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '149', 'Reporto - Saída de Estabelecimento de máquinas e outros quando adquiridos por beneficiários do REPORTO - Art. 166, I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '150', 'Reporto - Desembaraço aduaneiro de máquinas e outros quando adquiridos por beneficiários do REPORTO - Art. 166, II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '151', 'Repes - Desembaraço aduaneiro - bens sem similar nacional importados por beneficiários do REPES - Art. 171 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '152', 'Recine - Saída para beneficiário do regime - Art. 14, III da Lei 12.599/2012');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '153', 'Recine - Desembaraço aduaneiro por beneficiário do regime - Art. 14, IV da Lei 12.599/2012');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '154', 'Reif - Saída para beneficiário do regime - Lei 12.794/1013, art. 8, III');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '155', 'Reif - Desembaraço aduaneiro por beneficiário do regime - Lei 12.794/1013, art. 8, IV');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '156', 'Repnbl-Redes - Saída para beneficiário do regime - Lei nº 12.715/2012, art. 30, II');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '157', 'Recompe - Saída de matérias-primas e produtos intermediários para beneficiários do regime - Decreto nº 7.243/2010, art. 5º, I');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '158', 'Recompe - Saída de matérias-primas e produtos intermediários destinados a industrialização de equipamentos - Programa Estímulo Universidade-Empresa - Apoio à Inovação - Decreto nº 7.243/2010, art. 5º, III');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '159', 'Rio 2016 - Produtos nacionais, duráveis, uso e consumo dos eventos, adquiridos pelas pessoas jurídicas mencionadas no § 2o do art. 4o da Lei nº 12.780/2013 - Lei nº 12.780/2013, Art. 13');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '160', 'Regime Especial de Admissão Temporária nos Termos do Art. 2o da IN 1361/2013');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '161', 'Regime Especial de Admissão Temporária nos termos do art. 5o da IN 1361/2013');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (4, '162', 'Regime Especial de Admissão Temporária nos termos do art. 7o da IN 1361/2013 (Suspensão com pagamento de tributos diferidos até a duração do regime, limitado a 100% do valor original)');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '601', 'Equipamentos e outros destinados à pesquisa e ao desenvolvimento tecnológico - Art. 72 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '602', 'Equipamentos e outros destinados à empresas habilitadas no PDTI e PDTA utilizados em pesquisa e ao desenvolvimento tecnológico - Art. 73 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '603', 'Microcomputadores e outros de até R$11.000,00, unidades de disco, circuitos, etc, destinados a bens de informática ou automação. Centro-Oeste SUDAM SUDENE - Art. 142, I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '604', 'Microcomputadores e outros de até R$11.000,00, unidades de disco, circuitos, etc, destinados a bens de informática ou automação. - Art. 142, I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '605', 'Bens de informática não incluídos no art. 142 do Decreto 7.212/2010 - Produzidos no Centro-Oeste, SUDAM, SUDENE - Art. 143, I do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '606', 'Bens de informática não incluídos no art. 142 do Decreto 7.212/2010 - Art. 143, II do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '607', 'Padis - Art. 150 do Decreto 7.212/2010');
INSERT INTO `ipi_enquadramento` (id_ipi_operacao, codigo_ipi_enquadramento, desc_ipi_enquadramento) VALUES (5, '608', 'Patvd - Art. 158 do Decreto 7.212/2010');

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
